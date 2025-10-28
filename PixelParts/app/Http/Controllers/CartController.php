<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{

public function index()
{
    $cart = session()->get('cart', []);

    $ivaRate = 0.23;
    $cartItems = [];
    $total = [
        'totalSemIva' => 0,
        'totalIva' => 0,
        'totalComIva' => 0
    ];

    foreach ($cart as $pid => $item) {
        $unitPriceComIva = $item['price'];
        $unitPriceSemIva = $unitPriceComIva / (1 + $ivaRate);
        $unitIva = $unitPriceComIva - $unitPriceSemIva;

        $subtotalComIva = $unitPriceComIva * $item['quantity'];
        $subtotalSemIva = $unitPriceSemIva * $item['quantity'];
        $subtotalIva = $unitIva * $item['quantity'];

        $total['totalSemIva'] += $subtotalSemIva;
        $total['totalIva'] += $subtotalIva;
        $total['totalComIva'] += $subtotalComIva;

        $cartItems[$pid] = [
            'product_id' => $pid,
            'name' => $item['name'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'subtotalComIva' => $subtotalComIva,
            'subtotalSemIva' => $subtotalSemIva,
            'subtotalIva' => $subtotalIva,
        ];
    }

    return view('products.cart', [
        'cartItems' => $cartItems,
        'totalSemIva' => $total['totalSemIva'],
        'totalIva' => $total['totalIva'],
        'totalComIva' => $total['totalComIva'],
        'ivaRate' => $ivaRate
    ]);
}

    // Adicionar item
   public function add(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'sometimes|integer|min:1|max:999',
    ]);

    $product = Product::findOrFail($request->product_id);
    $quantityFromRequest = (int) $request->input('quantity', 1);

    $cart = session()->get('cart', []);

    if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity'] += $quantityFromRequest;
    } else {
        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $quantityFromRequest,
            'image' => $product->image
        ];
    }

    session()->put('cart', $cart);

    return response()->json([
        'success' => true,
        'cart_count' => array_sum(array_column($cart, 'quantity')),
        'message' => 'Produto adicionado ao carrinho!'
    ]);
}

public function remove($id)
{
    $cart = session()->get('cart', []);

    if(isset($cart[$id])) {
        $cart[$id]['quantity']--;

        if($cart[$id]['quantity'] <= 0) {
            unset($cart[$id]);
        }

        session()->put('cart', $cart);
    }

    // Definir IVA antes do loop
    $ivaRate = 0.23;

    // Calcula totais
    $total = [
        'totalSemIva' => 0,
        'totalIva' => 0,
        'totalComIva' => 0
    ];

    $cartItemsData = [];

    foreach($cart as $pid => $item) {
        $unitPriceComIva = $item['price'];
        $unitPriceSemIva = $unitPriceComIva / (1 + $ivaRate);
        $unitIva = $unitPriceComIva - $unitPriceSemIva;

        $subtotalComIva = $unitPriceComIva * $item['quantity'];
        $subtotalSemIva = $unitPriceSemIva * $item['quantity'];
        $subtotalIva = $unitIva * $item['quantity'];

        $total['totalSemIva'] += $subtotalSemIva;
        $total['totalIva'] += $subtotalIva;
        $total['totalComIva'] += $subtotalComIva;

        $cartItemsData[$pid] = [
            'subtotalComIva' => $subtotalComIva,
            'subtotalSemIva' => $subtotalSemIva,
            'subtotalIva' => $subtotalIva,
            'quantity' => $item['quantity']
        ];
    }

    return response()->json([
        'success' => true,
        'cartItems' => $cartItemsData,
        'total' => $total,
        'cart_count' => array_sum(array_column($cart, 'quantity'))
    ]);
}


}
