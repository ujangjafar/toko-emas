<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalRevenue = Order::where('status', 'selesai')->sum('total_price');

        $recentOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $lowStockProducts = Product::where('stock', '<', 10)
            ->limit(5)
            ->get();

        $salesChart = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total_price) as total_sales')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'recentOrders',
            'lowStockProducts',
            'salesChart'
        ));
    }
}