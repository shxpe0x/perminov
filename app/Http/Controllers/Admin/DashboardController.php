<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_users' => User::count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'low_stock_products' => Product::where('stock', '<', 10)->where('stock', '>', 0)->count(),
            'out_of_stock_products' => Product::where('stock', 0)->count(),
        ];

        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Low stock products
        $lowStockProducts = Product::where('stock', '<', 10)
            ->where('stock', '>', 0)
            ->orderBy('stock')
            ->take(10)
            ->get();

        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'lowStockProducts',
            'ordersByStatus'
        ));
    }
}
