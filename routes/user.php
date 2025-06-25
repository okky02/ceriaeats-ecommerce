<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\ShopController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ChatController;

Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {

    Route::get('/home', [HomeController::class, 'home'])->name('user.home.index');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('user.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('user.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('user.profile.destroy');

    Route::get('/shop', [ShopController::class, 'index'])->name('user.shop.index');
    Route::get('/shop/{id}', [ShopController::class, 'show'])->name('user.shop.show');

    Route::post('/product/{product}/review', [ReviewController::class, 'store'])->name('user.product.review.store');
    Route::delete('/product/review/{review}', [ReviewController::class, 'destroy'])
    ->middleware('auth')
    ->name('user.product.review.delete');
    
    Route::get('/cart', [CartController::class, 'index'])->name('user.cart.index');
    Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('user.cart.add');
    Route::delete('/cart-item/{id}', [CartController::class, 'removeItem'])->name('user.cart.remove');
    Route::put('/cart-item/{id}', [CartController::class, 'updateItem'])->name('user.cart.update');

    Route::post('/apply-voucher', [CartController::class, 'applyVoucher'])->name('user.apply-voucher');
    Route::post('/reset-voucher', [CartController::class, 'resetVoucher'])->name('user.reset-voucher');

    Route::post('/checkout', [CheckoutController::class, 'store'])->name('user.checkout');

    Route::get('/payment/{order}', [PaymentController::class, 'payment'])->name('user.payment-master');
    Route::post('/payment/{order}/confirm', [PaymentController::class, 'confirmPayment'])->name('user.payment.confirm');

    Route::get('/order', [OrderController::class, 'index'])->name('user.order.index');
    Route::get('/order/{order}/export-pdf', [OrderController::class, 'exportPdf'])->name('user.order.exportPdf');

    Route::get('/about', function () {
        return view('user.about-master');
    })->name('user.about-master');

    Route::get('/chat', [ChatController::class, 'index'])->name('user.chat');
    Route::get('/chat/fetch-messages', [ChatController::class, 'fetchMessages']);
    Route::post('/chat/send-message', [ChatController::class, 'sendMessage']);
    
});

