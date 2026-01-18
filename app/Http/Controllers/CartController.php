<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Carbon\Carbon;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', ['items' => []]);

        $subtotalCents = $this->cartSubtotalCents($cart);

        $coupon = session('cart.coupon');
        $discountCents = 0;

        if ($coupon) {
            if ($coupon['type'] === 'percent') {
                $discountCents = (int) floor($subtotalCents * ((int) $coupon['value'] / 100));
            } else {
                $discountCents = (int) $coupon['value'];
            }

            $discountCents = min($discountCents, $subtotalCents);
        }

        $totalCents = max($subtotalCents - $discountCents, 0);


        return view('cart.index', compact(
            'cart',
            'subtotalCents',
            'discountCents',
            'totalCents'
        ));
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

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:50'],
        ]);

        $code = strtoupper(trim($request->input('code')));

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->active) {
            return back()->withErrors(['code' => 'Nieprawidłowy kupon.']);
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return back()->withErrors(['code' => 'Kupon wygasł.']);
        }

        if ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
            return back()->withErrors(['code' => 'Limit użyć kuponu został wyczerpany.']);
        }

        $cart = session('cart', ['items' => []]);
        $subtotalCents = $this->cartSubtotalCents($cart);

        if ($coupon->min_order_cents !== null && $subtotalCents < $coupon->min_order_cents) {
            return back()->withErrors(['code' => 'Za niska wartość koszyka dla tego kuponu.']);
        }

        session()->put('cart.coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
        ]);

        return back()->with('success', 'Kupon zastosowany.');
    }

    public function removeCoupon()
    {
        session()->forget('cart.coupon');
        return back()->with('success', 'Kupon usunięty.');
    }

    private function cartSubtotalCents(array $cart): int
    {
        $subtotal = 0;
        foreach (($cart['items'] ?? []) as $productId => $item) {
            $subtotal += (int) ($item['price_cents'] ?? 0) * (int) ($item['quantity'] ?? 0);
        }
        return $subtotal;
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Koszyk wyczyszczony.');
    }
}

