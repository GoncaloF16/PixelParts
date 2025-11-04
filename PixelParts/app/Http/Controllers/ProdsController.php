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

    // Filtrar por categoria via menu (parâmetro único)
    if ($request->has('categoria') && $request->categoria && !is_array($request->categoria)) {
        $query->whereHas('category', function($q) use ($request) {
            $q->where('slug', $request->categoria);
        });
    }

    // Filtrar por múltiplas categorias (checkboxes)
    if ($request->has('categoria') && is_array($request->categoria)) {
        $query->whereHas('category', function($q) use ($request) {
            $q->whereIn('slug', $request->categoria);
        });
    }

    // Filtrar por múltiplas marcas (checkboxes)
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
    $brands = Product::select('brand')
        ->distinct()
        ->whereNotNull('brand')
        ->orderBy('brand')
        ->pluck('brand');

    $categories = Category::orderBy('name')->get();

    return view('products.products', compact('products', 'categories', 'brands'));
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

        return view('products.details', [
            'product' => $product,
            'averageRating' => $averageRating
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
