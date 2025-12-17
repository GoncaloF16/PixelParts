<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index() {

        $products = Product::latest()->take(6)->get();
        return view ('index', compact('products'));

    }

    public function about() {

        return view('about');

    }

    public function faq() {

        return view('faq');

    }

    public function searchbar(Request $request)
{
    $query = $request->input('q');

    $produtos = Produto::query()
        ->when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('nome', 'like', "%{$query}%")
                         ->orWhere('descricao', 'like', "%{$query}%")
                         ->orWhere('marca', 'like', "%{$query}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(12);

    return view('products.products', compact('produtos', 'query'));
}

    public function profile()
    {
        $user = Auth::user();

        // Obter encomendas do utilizador, ordenadas por mais recentes
        $orders = Order::where('user_id', $user->id)
            ->whereNotNull('stripe_id')
            ->with(['products.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('profile.index', compact('user', 'orders'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return redirect()->route('profile')->with('success', 'Perfil atualizado com sucesso!');
    }
}
