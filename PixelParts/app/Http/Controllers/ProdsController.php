<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductReview;

class ProdsController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(9);
        $brands = Product::select('brand')->distinct()->pluck('brand');
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
