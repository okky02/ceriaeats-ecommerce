<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cartItemCount = 0;
            if (Auth::check()) {
                $cart = Cart::withCount('items')->where('user_id', Auth::id())->first();
                $cartItemCount = $cart ? $cart->items_count : 0;
            }
            $view->with('cartItemCount', $cartItemCount);
        });
    }
}
