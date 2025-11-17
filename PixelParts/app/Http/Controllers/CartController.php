<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

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

public function order (Request $request)
{
    $order = Order::create([
        'user_id' => auth()->user()->id,
    ]);
    $amount = 0;
    foreach (session('cart') as $key => $value) {
        $order->products()->create([
            'product_id' => $key,
            'quantity' => $value['quantity'],
            'price' => $value['price']
        ]);

        $amount = $amount + ($value['quantity'] * $value['price']);
    }

    $order->amount = $amount;
    $order->save();

    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

    $successURL = route('order.success').'?session_id={CHECKOUT_SESSION_ID}&order_id='. $order->id;

    $response = $stripe->checkout->sessions->create([
    'success_url' => $successURL,
    'customer_email' => auth()->user()->email,
    'line_items' => [
        [
        'price_data' => [
            'product_data' => [
                'name' => "Shopping"
            ],
            'unit_amount' => $amount * 100,
            'currency' => 'eur',
        ],
        'quantity' => 1
        ],
    ],
    'mode' => 'payment',
    ]);

    return redirect($response['url']);
}

public function orderSuccess(Request $request)
{
    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

    $session = $stripe->checkout->sessions->retrieve($request->session_id);

    if ($session->status == "complete") {
        $order = Order::find($request->order_id);
        $order->status = 1;
        $order->stripe_id = $session->id;
        $order->save();

        return redirect()->route('home')->with('success', 'Pedido realizado com sucesso!');
    }

    $order = Order::find($request->order_id);
        $order->status = 2;
        $order->save();

    dd('Failed.');
}
}
