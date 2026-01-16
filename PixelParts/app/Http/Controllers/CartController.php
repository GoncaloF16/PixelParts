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
            'image' => $item['image'] ?? null,
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
            'price' => $product->discount_percentage && $product->discount_percentage > 0
                ? $product->discounted_price
                : $product->price,
            'quantity' => $quantityFromRequest,
            'image' => $product->image
        ];
    }

    session()->put('cart', $cart);

    // Track abandoned cart
    $user = auth()->user();
    if ($user) {
        \App\Models\AbandonedCart::updateOrCreate(
            ['user_id' => $user->id, 'recovered_at' => null, 'email_sent_at' => null],
            ['cart_data' => $cart]
        );
    }

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

public function showCheckout()
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'O seu carrinho está vazio.');
    }

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

    return view('checkout.index', [
        'cartItems' => $cartItems,
        'totalSemIva' => $total['totalSemIva'],
        'totalIva' => $total['totalIva'],
        'totalComIva' => $total['totalComIva'],
        'ivaRate' => $ivaRate
    ]);
}

public function processCheckout(Request $request)
{
    $validated = $request->validate([
        'billing_name' => 'required|string|max:255',
        'billing_address' => 'required|string|max:255',
        'billing_city' => 'required|string|max:255',
        'billing_postal_code' => 'required|string|max:20',
        'billing_country' => 'required|string|max:255',
        'billing_phone' => 'required|string|max:20',
        'billing_nif' => 'nullable|string|max:20',
        'shipping_name' => 'required|string|max:255',
        'shipping_address' => 'required|string|max:255',
        'shipping_city' => 'required|string|max:255',
        'shipping_postal_code' => 'required|string|max:20',
        'shipping_country' => 'required|string|max:255',
        'shipping_phone' => 'required|string|max:20',
    ]);

    // Update user's saved addresses
    $user = auth()->user();
    $user->update($validated);

    // Create order with address snapshot
    $order = Order::create([
        'user_id' => $user->id,
        'billing_name' => $validated['billing_name'],
        'billing_address' => $validated['billing_address'],
        'billing_city' => $validated['billing_city'],
        'billing_postal_code' => $validated['billing_postal_code'],
        'billing_country' => $validated['billing_country'],
        'billing_phone' => $validated['billing_phone'],
        'billing_nif' => $validated['billing_nif'] ?? null,
        'shipping_name' => $validated['shipping_name'],
        'shipping_address' => $validated['shipping_address'],
        'shipping_city' => $validated['shipping_city'],
        'shipping_postal_code' => $validated['shipping_postal_code'],
        'shipping_country' => $validated['shipping_country'],
        'shipping_phone' => $validated['shipping_phone'],
    ]);

    $amount = 0;
    foreach (session('cart') as $key => $value) {
        $linePriceCents = (int) round($value['price'] * 100);
        $order->products()->create([
            'product_id' => $key,
            'quantity' => $value['quantity'],
            'price' => $linePriceCents
        ]);

        $amount = $amount + ($value['quantity'] * $value['price']);
    }

    // Store in cents (with correct rounding)
    $orderTotalCents = (int) round($amount * 100);
    $order->amount = $orderTotalCents;
    $order->save();

    // Mark any abandoned cart as recovered since user is placing order
    if ($user) {
        \App\Models\AbandonedCart::where('user_id', $user->id)
            ->whereNull('recovered_at')
            ->update(['recovered_at' => now()]);
    }

    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

    $successURL = route('order.success').'?session_id={CHECKOUT_SESSION_ID}&order_id='. $order->id;

    $response = $stripe->checkout->sessions->create([
        'success_url' => $successURL,
        'customer_email' => $user->email,
        'line_items' => [
            [
                'price_data' => [
                    'product_data' => [
                        'name' => "Shopping"
                    ],
                    'unit_amount' => $orderTotalCents,
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

    $order = Order::findOrFail($request->order_id);

    if ($session->status == "complete" && $session->payment_status == "paid") {
        // Pagamento bem-sucedido
        $order->status = 1; // Processando
        $order->stripe_id = $session->id;
        $order->save();

        // Decrementar stock dos produtos
        foreach ($order->products as $orderProduct) {
            $product = Product::find($orderProduct->product_id);
            if ($product) {
                $product->stock -= $orderProduct->quantity;
                $product->save();
            }
        }

        // Limpar carrinho
        session()->forget('cart');

        return redirect()->route('home')->with('success', 'Pedido realizado com sucesso!');
    }

    // Payment not completed
    $order->status = 0; // Keep as Pending (don't delete the order)
    $order->save();

    return redirect()->route('cart.index')->with('error', 'Pagamento não foi concluído. Por favor, tente novamente.');
}

public function recover($token)
{
    $abandonedCart = \App\Models\AbandonedCart::where('token', $token)
        ->whereNull('recovered_at')
        ->first();

    if (!$abandonedCart) {
        return redirect()->route('home')->with('error', 'Link de recuperação inválido ou expirado.');
    }

    // Restore cart data to session
    session()->put('cart', $abandonedCart->cart_data);

    // Mark as recovered
    $abandonedCart->markAsRecovered();

    return redirect()->route('cart.index')->with('success', 'O teu carrinho foi recuperado com sucesso!');
}
}
