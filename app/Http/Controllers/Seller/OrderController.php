<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->query('status');

        $orders = Order::query()
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('seller.orders.index', [
            'orders' => $orders,
            'status' => $status,
            'statuses' => Order::STATUSES,

        ]);
    }

    public function show(Order $order)
    {
        $order->load('items');

        return view('seller.orders.show', [
            'order' => $order,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', Order::STATUSES)],
        ]);

        $order->update([
            'status' => $data['status'],
        ]);

        return redirect()->route('seller.orders.show', $order)->with('success', 'Status zmieniony.');
    }
}
