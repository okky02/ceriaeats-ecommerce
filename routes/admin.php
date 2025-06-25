<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleChangeController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ChatController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');
    
    Route::get('/category', [CategoryController::class, 'index'])->name('admin.category.index');
    Route::post('/category', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');    
    
    Route::get('/product', [ProductController::class, 'index'])->name('admin.product.index');
    Route::post('/product', [ProductController::class, 'store'])->name('admin.product.store');
    Route::put('/product/{id}', [ProductController::class, 'update'])->name('admin.product.update');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('admin.product.destroy');

    Route::get('/report', [ReportController::class, 'index'])->name('admin.report.index');
    Route::get('/report/export-pdf', [ReportController::class, 'exportPdf'])->name('admin.report.exportPdf');

    Route::get('/order', [OrderController::class, 'index'])->name('admin.order.index');
    Route::post('/order/{order}/approve', [OrderController::class, 'approve'])->name('admin.order.approve');
    Route::post('/order/{order}/deny', [OrderController::class, 'deny'])->name('admin.order.deny');
    Route::patch('/order/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.order.updateStatus');
    Route::get('/order/{order}/export-pdf', [OrderController::class, 'exportPdf'])->name('admin.order.exportPdf');

    Route::get('/chat', [ChatController::class, 'index'])->name('admin.chat');
    Route::get('/chat/fetch-messages/{userId}', [ChatController::class, 'fetchMessages']);
    Route::post('/chat/send-message', [ChatController::class, 'sendMessage']);
    Route::get('/chat/user-list', [ChatController::class, 'userListPartial']);

    Route::get('/voucher', [VoucherController::class, 'index'])->name('admin.voucher.index');
    Route::post('/voucher', [VoucherController::class, 'store'])->name('admin.voucher.store');
    Route::delete('/voucher/{voucher}', [VoucherController::class, 'destroy'])->name('admin.voucher.destroy');
    Route::put('/voucher/{voucher}', [VoucherController::class, 'update'])->name('admin.voucher.update');

    Route::get('/payment', [PaymentMethodController::class, 'index'])->name('admin.payment-methods.index');
    Route::post('/payment', [PaymentMethodController::class, 'store'])->name('admin.payment-methods.store');
    Route::put('/payment/{id}', [PaymentMethodController::class, 'update'])->name('admin.payment-methods.update');
    Route::delete('/payment/{id}', [PaymentMethodController::class, 'destroy'])->name('admin.payment-methods.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::patch('/users/{id}/change-role', [RoleChangeController::class, 'update'])->name('admin.users.change-role');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});
