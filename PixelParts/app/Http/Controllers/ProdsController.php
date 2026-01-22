<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductReview;

class ProdsController extends Controller
{
    public function index(Request $request)
{
    $query = Product::with('category');

    // === Filtering Logic ===
    // Single category filter (from menu)
    if ($request->has('categoria') && $request->categoria && !is_array($request->categoria)) {
        $query->whereHas('category', function($q) use ($request) {
            $q->where('slug', $request->categoria);
        });
    }

    // Multiple categories filter (checkboxes)
    if ($request->has('categoria') && is_array($request->categoria)) {
        $query->whereHas('category', function($q) use ($request) {
            $q->whereIn('slug', $request->categoria);
        });
    }

    // Multiple brands filter (checkboxes)
    if ($request->has('brand') && is_array($request->brand)) {
        $brands = $request->brand;
        $query->where(function($q) use ($brands) {
            foreach ($brands as $brand) {
                $q->orWhere('brand', 'LIKE', $brand);
            }
        });
    }

    // Filtrar por pesquisa de texto (nome do produto OU categoria)
    if ($request->has('q') && $request->q) {
        $search = $request->q;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('brand', 'like', "%{$search}%")
              ->orWhereHas('category', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
              });
        });
    }

    // Paginação
    $products = $query->paginate(9)->withQueryString();

    // Buscar TODAS as marcas e categorias
    $brands = Product::whereNotNull('brand')
        ->where('brand', '!=', '')
        ->distinct()
        ->orderBy('brand')
        ->pluck('brand')
        ->map(function($brand) {
            return trim($brand);
        })
        ->filter()
        ->unique()
        ->values();

    $categories = Category::orderBy('name')->get();

    return view('products.products', compact('products', 'categories', 'brands'));
}


    public function searchAjax(Request $request)
    {
        $search = trim($request->get('q', ''));

        if ($search === '') {
            return response()->json([]);
        }

        $products = Product::with('category')
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            })
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->limit(8)
            ->get();

        $results = $products->map(function ($product) {
            $image = $product->image;

            if ($image && !preg_match('/^https?:\/\//', $image)) {
                $image = asset('storage/' . $image);
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $image,
                'price' => $product->discounted_price ?? $product->price,
            ];
        });

        return response()->json($results);
    }


    public function show($slug)
    {
        $product = Product::with([
            'category',
            'specifications',
            'features',
            'compatibility',
            'reviews.user'
        ])->where('slug', $slug)->firstOrFail();

        $averageRating = $product->averageRating();

        // Get recommended products (same category or same type, excluding current product)
        $recommendedProducts = Product::with('category')
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->where(function($query) use ($product) {
                $query->where('category_id', $product->category_id)
                      ->orWhereHas('category', function($q) use ($product) {
                          $q->where('type', $product->category->type);
                      });
            })
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // If not enough products, get from any category
        if ($recommendedProducts->count() < 4) {
            $additionalProducts = Product::where('id', '!=', $product->id)
                ->where('stock', '>', 0)
                ->whereNotIn('id', $recommendedProducts->pluck('id'))
                ->inRandomOrder()
                ->limit(4 - $recommendedProducts->count())
                ->get();

            $recommendedProducts = $recommendedProducts->merge($additionalProducts);
        }

        return view('products.details', [
            'product' => $product,
            'averageRating' => $averageRating,
            'recommendedProducts' => $recommendedProducts
        ]);
    }

    public function storeReview(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        ProductReview::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Avaliação enviada com sucesso!');
    }

    public function destroy(ProductReview $review)

    {

        if($review->user_id !== auth()->id()){
            abort(403);
        }

        $review->delete();

        return redirect()->back()->with('success', 'Comentário removido com sucesso!');
    }

}
