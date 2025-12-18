<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_users' => User::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::whereIn('status', ['completed', 'processing', 'shipped'])->sum('total'),
            'low_stock_products' => Product::where('stock', '<=', 5)->count(),
        ];

        $recent_orders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        $top_products = Product::select('products.*', DB::raw('COUNT(order_items.id) as orders_count'))
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.id')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'top_products'));
    }
}