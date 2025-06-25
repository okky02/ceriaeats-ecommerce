<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $recentReviews = Review::with(['product', 'user'])->latest()->get();

        $totalSales = Order::where('status', 'approved')->sum('total');

        $orderStatusCounts = [
            'unpaid' => Order::where('status', 'unpaid')->count(),
            'process' => Order::where('status', 'waiting_confirmation')->count(),
            'approved' => Order::where('status', 'approved')->count(),
            'denied' => Order::where('status', 'denied')->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(5)->get();

        $monthlySales = Order::select(
            DB::raw("SUM(total) as total"),
            DB::raw("MONTH(created_at) as month")
        )
        ->where('status', 'approved')
        ->whereYear('created_at', date('Y')) // tahun sekarang
        ->groupBy(DB::raw("MONTH(created_at)"))
        ->pluck('total', 'month')
        ->toArray();
    
        // Format data untuk chart
        $months = [];
        $salesData = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->locale('id')->monthName; // Nama bulan
            $salesData[] = $monthlySales[$i] ?? 0; // Isi dengan 0 jika tidak ada penjualan
        }

        $topProducts = DB::table('order_items')
        ->select('products.nama_produk', DB::raw('SUM(order_items.quantity) as total_terjual'))
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.status', 'approved') // hanya dari pesanan yang disetujui
        ->groupBy('products.nama_produk')
        ->orderByDesc('total_terjual')
        ->limit(5)
        ->get();

        return view('admin.dashboard-master', compact(
            'totalOrders', 
            'totalUsers', 
            'totalProducts', 
            'totalCategories', 
            'totalSales', 
            'orderStatusCounts',
            'recentOrders',
            'recentReviews',
            'months', 
            'salesData',
            'topProducts'
        ));
    }
}