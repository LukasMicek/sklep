<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', ['items' => []]);

        $totalCents = 0;
        foreach ($cart['items'] as $item) {
            $totalCents += $item['price_cents'] * $item['quantity'];
        }

        return view('cart.index', [
            'cart' => $cart,
            'totalCents' => $totalCents,
        ]);
    }

    public function add(Request $request, Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $qty = (int) $data['quantity'];

        $cart = session()->get('cart', ['items' => []]);

        $currentQty = $cart['items'][$product->id]['quantity'] ?? 0;
        $newQty = $currentQty + $qty;

        if ($newQty > $product->stock) {
            return back()->with('error', 'Brak tyle sztuk na stanie.');
        }

        $cart['items'][$product->id] = [
            'product_id' => $product->id,
            'slug' => $product->slug,
            'name' => $product->name,
            'price_cents' => $product->price_cents,
            'quantity' => $newQty,
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Dodano do koszyka.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $qty = (int) $data['quantity'];

        $cart = session()->get('cart', ['items' => []]);

        if (!isset($cart['items'][$product->id])) {
            return redirect()->route('cart.index');
        }

        if ($qty === 0) {
            unset($cart['items'][$product->id]);
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Usunięto pozycję.');
        }

        if ($qty > $product->stock) {
            return back()->with('error', 'Brak tyle sztuk na stanie.');
        }

        $cart['items'][$product->id]['quantity'] = $qty;
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Zmieniono ilość.');
    }

    public function remove(Product $product)
    {
        $cart = session()->get('cart', ['items' => []]);

        unset($cart['items'][$product->id]);

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Usunięto pozycję.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Koszyk wyczyszczony.');
    }
}

