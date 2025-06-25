<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Message;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Notifikasi pesanan belum dilihat
        $unseenOrders = Order::where('is_seen_by_admin', false)->count();
        view()->share('unseenOrders', $unseenOrders);

        // Notifikasi chat belum dibaca (hanya untuk admin)
        View::composer('admin.home-partials.sidebar', function ($view) {
            if (Auth::check() && Auth::user()->role === 'admin') {
                $unreadChatCount = Message::whereHas('receiver', function ($q) {
                    $q->where('role', 'admin');
                })
                ->where('is_read', false)
                ->count();
        
                $view->with('unreadChatCount', $unreadChatCount);
            }
        });        
    }
}
