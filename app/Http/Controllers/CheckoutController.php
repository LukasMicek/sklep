<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = session('cart', ['items' => []]);

        // policz subtotal na podstawie koszyka (to samo co w place())
        $subtotalCents = 0;

        foreach (($cart['items'] ?? []) as $productId => $item) {
            $product = Product::find($productId);
            if (!$product)
                continue;

            $qty = (int) ($item['quantity'] ?? 0);
            $subtotalCents += $product->price_cents * $qty;
        }

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

        return view('checkout.show', compact('cart', 'subtotalCents', 'discountCents', 'totalCents', 'coupon'));
    }

    public function place(Request $request)
    {
        $cart = session()->get('cart', ['items' => []]);

        if (empty($cart['items'])) {
            return redirect()->route('cart.index')->with('error', 'Koszyk jest pusty.');
        }

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
        ]);

        return DB::transaction(function () use ($cart, $data) {
            $productIds = array_keys($cart['items']);
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            $totalCents = 0;

            foreach ($cart['items'] as $productId => $item) {
                $product = $products->get((int) $productId);

                if (!$product || !$product->is_active) {
                    abort(400, 'Produkt niedostępny.');
                }

                if ($item['quantity'] > $product->stock) {
                    abort(400, 'Brak produktu na stanie: ' . $product->name);
                }

                $totalCents += $product->price_cents * $item['quantity'];
            }

            $coupon = session('cart.coupon');

            $subtotalCents = $totalCents;
            $discountCents = 0;

            if ($coupon) {
                if ($coupon['type'] === 'percent') {
                    $discountCents = (int) floor($subtotalCents * ((int) $coupon['value'] / 100));
                } else {
                    $discountCents = (int) $coupon['value'];
                }

                $discountCents = min($discountCents, $subtotalCents);
            }

            $totalAfterDiscount = max($subtotalCents - $discountCents, 0);


            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'new',
                'subtotal_cents' => $subtotalCents,
                'discount_cents' => $discountCents,
                'coupon_code' => $coupon['code'] ?? null,
                'total_cents' => $totalAfterDiscount,
                'full_name' => $data['full_name'],
                'phone' => $data['phone'] ?? null,
                'address_line' => $data['address_line'],
                'city' => $data['city'],
                'postal_code' => $data['postal_code'],
            ]);

            if ($coupon) {
                \App\Models\Coupon::where('code', $coupon['code'])->increment('used_count');
            }

            session()->forget('cart.coupon');


            \App\Models\OrderStatusChange::create([
                'order_id' => $order->id,
                'old_status' => null,
                'new_status' => $order->status,
                'changed_by_user_id' => auth()->id(),
            ]);


            foreach ($cart['items'] as $productId => $item) {
                $product = $products->get((int) $productId);

                $lineTotal = $product->price_cents * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price_cents' => $product->price_cents,
                    'quantity' => $item['quantity'],
                    'line_total_cents' => $lineTotal,
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            session()->forget('cart');

            Mail::to(auth()->user()->email)->send(new OrderPlaced($order));

            return redirect()->route('orders.mine')->with('success', 'Zamówienie złożone.');
        });
    }
}

