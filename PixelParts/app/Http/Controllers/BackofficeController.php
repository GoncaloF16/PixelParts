<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Exports\StockExport;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
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
            ->paginate(5);

        return view('backoffice.dashboard', compact('totalUsers', 'totalProducts', 'products'));

    }

    public function users(Request $request) {
        $query = DB::table('users');

        // Filtro de pesquisa
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro de role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('backoffice.users', compact('users'));
    }      public function storeUser(Request $request) {
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

        $products = $query->paginate(10);

        return view('backoffice.stock', compact('products', 'allCategories'));
    }

    public function exportStockExcel(Request $request)
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

        return Excel::download(new StockExport($products), 'stock.xlsx');
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

        // Adicionar especificações
        if ($request->has('specifications')) {
            $specs = $request->input('specifications');

            if (isset($specs['key']) && isset($specs['value']) &&
                is_array($specs['key']) && is_array($specs['value'])) {
                foreach ($specs['key'] as $index => $key) {
                    if (!empty(trim($key)) && isset($specs['value'][$index]) && !empty(trim($specs['value'][$index]))) {
                        $product->specifications()->create([
                            'key' => trim($key),
                            'value' => trim($specs['value'][$index])
                        ]);
                    }
                }
            }
        }

        // Adicionar características
        if ($request->has('features') && is_array($request->input('features'))) {
            $features = array_filter($request->input('features'), function($feature) {
                return !empty(trim($feature));
            });

            if (!empty($features)) {
                foreach ($features as $feature) {
                    $product->features()->create(['feature' => trim($feature)]);
                }
            }
        }

        // Adicionar compatibilidade
        if ($request->has('compatibility') && is_array($request->input('compatibility'))) {
            $compatibilities = array_filter($request->input('compatibility'), function($comp) {
                return !empty(trim($comp));
            });

            if (!empty($compatibilities)) {
                foreach ($compatibilities as $comp) {
                    $product->compatibility()->create(['compatible_with' => trim($comp)]);
                }
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Produto adicionado com sucesso!',
                'product' => $product
            ]);
        }

        return redirect()->back()->with('success', 'Produto adicionado com sucesso!');
    }


    public function updateProduct(Request $request, Product $produto)
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
            if ($produto->image && Storage::disk('public')->exists($produto->image)) {
                Storage::disk('public')->delete($produto->image);
            }
            $data['image'] = $request->file('image')->store('produtos', 'public');
        }

        $data['slug'] = Str::slug($data['name']);
        $produto->update($data);

        // Atualizar especificações
        $produto->specifications()->delete();
        if ($request->has('specifications')) {
            $specs = $request->input('specifications');

            if (isset($specs['key']) && isset($specs['value']) &&
                is_array($specs['key']) && is_array($specs['value'])) {
                foreach ($specs['key'] as $index => $key) {
                    if (!empty(trim($key)) && isset($specs['value'][$index]) && !empty(trim($specs['value'][$index]))) {
                        $produto->specifications()->create([
                            'key' => trim($key),
                            'value' => trim($specs['value'][$index])
                        ]);
                    }
                }
            }
        }

        // Atualizar características
        $produto->features()->delete();
        if ($request->has('features') && is_array($request->input('features'))) {
            $features = array_filter($request->input('features'), function($feature) {
                return !empty(trim($feature));
            });

            if (!empty($features)) {
                foreach ($features as $feature) {
                    $produto->features()->create(['feature' => trim($feature)]);
                }
            }
        }

        // Atualizar compatibilidade
        $produto->compatibility()->delete();
        if ($request->has('compatibility') && is_array($request->input('compatibility'))) {
            $compatibilities = array_filter($request->input('compatibility'), function($comp) {
                return !empty(trim($comp));
            });

            if (!empty($compatibilities)) {
                foreach ($compatibilities as $comp) {
                    $produto->compatibility()->create(['compatible_with' => trim($comp)]);
                }
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Produto atualizado com sucesso!', 'product' => $produto]);
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

    // Compatibilidade com rota atual: proxy para deleteProduct
    public function destroyProduct(Request $request, Product $produto)
    {
        // Reutilizar a lógica existente de deleteProduct
        return $this->deleteProduct($request, $produto);
    }

    // Remoção em massa de produtos selecionados
    public function bulkDelete(Request $request)
    {
        $data = $request->validate([
            'selected' => 'required|array',
            'selected.*' => 'integer|exists:products,id',
            'search' => 'nullable|string',
            'category' => 'nullable|string',
        ], [
            'selected.required' => 'Selecione pelo menos um produto para apagar.',
        ]);

        $ids = $data['selected'];

        // Apagar imagens associadas quando existirem
        $products = Product::whereIn('id', $ids)->get();
        foreach ($products as $product) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
        }

        // Usar destroy para disparar eventos de modelo (se existirem)
        Product::destroy($ids);

        // Redirecionar mantendo filtros (sem a página para evitar ficar numa página vazia)
        $redirectParams = [];
        if ($request->filled('search')) $redirectParams['search'] = $request->input('search');
        if ($request->filled('category')) $redirectParams['category'] = $request->input('category');

        return redirect()->route('backoffice.stock', $redirectParams)
            ->with('success', 'Produtos selecionados apagados com sucesso!');
    }

    // Obter dados completos do produto
    public function getProduct(Product $product)
    {
        $product->load(['category', 'specifications', 'features', 'compatibility']);

        return response()->json([
            'product' => $product
        ]);
    }
}
