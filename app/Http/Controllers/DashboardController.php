<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'seller') {
            return redirect()->route('seller.dashboard');
        }

        $stats = [
            'orders_total' => Order::where('user_id', $user->id)->count(),
            'orders_new' => Order::where('user_id', $user->id)->where('status', 'new')->count(),
            'orders_in_progress' => Order::where('user_id', $user->id)->where('status', 'in_progress')->count(),
            'orders_shipped' => Order::where('user_id', $user->id)->where('status', 'shipped')->count(),
            'spent_total_cents' => Order::where('user_id', $user->id)
                ->where('status', '!=', 'canceled')
                ->sum('total_cents'),
        ];

        $recentOrders = Order::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $cart = session('cart', ['items' => []]);
        $cartItemsCount = 0;
        foreach (($cart['items'] ?? []) as $item) {
            $cartItemsCount += (int)($item['quantity'] ?? 0);
        }

        return view('dashboard', compact('stats', 'recentOrders', 'cartItemsCount'));
    }
}
