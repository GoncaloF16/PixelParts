<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // Mostrar carrinho
    public function index()
    {
        $cartItems = session()->get('cart', []);
        $cartTotal = collect($cartItems)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('products.cart', compact('cartItems', 'cartTotal'));
    }

    // Adicionar item
   public function add(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'sometimes|integer|min:1|max:999',  // 'sometimes' significa que é opcional
    ]);

    $product = Product::findOrFail($request->product_id);
    $quantityFromRequest = (int) $request->input('quantity', 1);  // Usa o valor do request, ou 1 como padrão

    $cart = session()->get('cart', []);

    if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity'] += $quantityFromRequest;  // Adiciona à quantidade existente
    } else {
        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $quantityFromRequest,  // Usa o valor do request ou o padrão (1)
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

    // Remover item
 public function remove($id)
{
    $cart = session()->get('cart', []);

    if(isset($cart[$id])) {
        $cart[$id]['quantity']--;

        if($cart[$id]['quantity'] <= 0) {
            unset($cart[$id]);
            $quantity = 0;
        } else {
            $quantity = $cart[$id]['quantity'];
        }

        session()->put('cart', $cart);

        // Recalcular totais
        $totalComIva = $totalSemIva = $totalIva = 0;
        $ivaRate = 0.23;
        foreach($cart as $item){
            $unitPriceComIva = $item['price'];
            $unitPriceSemIva = $unitPriceComIva / (1 + $ivaRate);
            $unitIva = $unitPriceComIva - $unitPriceSemIva;

            $totalSemIva += $unitPriceSemIva * $item['quantity'];
            $totalIva += $unitIva * $item['quantity'];
            $totalComIva += $unitPriceComIva * $item['quantity'];
        }

        return response()->json([
            'success' => true,
            'quantity' => $quantity,
            'cart_count' => count($cart),
            'total_com_iva_formatted' => number_format($totalComIva, 2, ',', '.'),
            'total_sem_iva_formatted' => number_format($totalSemIva, 2, ',', '.'),
            'total_iva_formatted' => number_format($totalIva, 2, ',', '.')
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Item não encontrado']);
}

}
