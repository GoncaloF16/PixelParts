<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BackofficeController extends Controller
{
    public function index() {

        $totalUsers = DB::table('users')->count();
        $totalProducts = DB::table('products')->count();

        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.name', 'products.stock', 'categories.name as category_name')
            ->where('products.stock', '<', 10)
            ->orderBy('products.stock', 'asc')
            ->get();

        return view('backoffice.dashboard', compact('totalUsers', 'totalProducts', 'products'));

    }

    public function users() {
        $users = DB::table('users')->get();

        return view('backoffice.users', compact('users'));
    }

      public function storeUser(Request $request) {
        $id = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('123456'),
        ]);

        $user = DB::table('users')->where('id', $id)->first();
        return response()->json(['message' => 'Utilizador adicionado com sucesso', 'user' => $user]);
    }

    public function updateUser(Request $request, $id) {
        DB::table('users')->where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user = DB::table('users')->where('id', $id)->first();
        return response()->json(['message' => 'Utilizador atualizado com sucesso', 'user' => $user]);
    }

    public function deleteUser($id) {
        DB::table('users')->where('id', $id)->delete();
        return response()->json(['message' => 'Utilizador excluído com sucesso', 'id' => $id]);
    }

    public function stock(Request $request) {

        $search = $request->input('search');
        $category = $request->input('category');

        $allCategories = Category::all();

        $query = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name');

        if ($search) {
            $query->where('products.name', 'like', '%' . $search . '%');
        }

        if ($category) {
            $query->where('categories.name', $category);
        }

        $products = $query->get();

        return view('backoffice.stock', compact('products', 'allCategories'));
    }

    public function exportStockPdf(Request $request)
{
    $query = DB::table('products')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->select('products.*', 'categories.name as category_name');

    if ($request->filled('search')) {
        $query->where('products.name', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('category')) {
        $query->where('categories.name', $request->category);
    }

    $products = $query->get();

    $pdf = Pdf::loadView('backoffice.stock-pdf', compact('products'));
    return $pdf->download('stock.pdf');
}


    public function storeProduct(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('produtos', 'public');
        }

        $data['slug'] = Str::slug($data['name']);
        $product = Product::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Produto adicionado com sucesso!',
                'product' => $product
            ]);
        }

        return redirect()->back()->with('success', 'Produto adicionado com sucesso!');
    }


    public function updateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Apagar imagem antiga
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('produtos', 'public');
        }

        $data['slug'] = Str::slug($data['name']);
        $product->update($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Produto atualizado com sucesso!', 'product' => $product]);
        }

        return redirect()->back()->with('success', 'Produto atualizado com sucesso!');
    }

    // Deletar produto
    public function deleteProduct(Request $request, Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Produto apagado com sucesso!']);
        }

        return redirect()->back()->with('success', 'Produto apagado com sucesso!');
    }
}
