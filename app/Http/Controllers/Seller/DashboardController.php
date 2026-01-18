<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'categories' => Category::count(),
            'products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'low_stock' => Product::where('stock', '<=', 5)->count(),
            'orders_total' => Order::count(),
            'orders_new' => Order::where('status', 'new')->count(),
            'orders_in_progress' => Order::where('status', 'in_progress')->count(),
        ];

        $recentOrders = Order::orderByDesc('created_at')
            ->limit(8)
            ->get();

        $lowStockProducts = Product::where('stock', '<=', 5)
            ->orderBy('stock')
            ->limit(8)
            ->get();

        return view('seller.dashboard', compact('stats', 'recentOrders', 'lowStockProducts'));
    }
}
