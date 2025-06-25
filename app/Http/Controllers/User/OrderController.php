<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use PDF;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product', 'paymentProof.paymentMethod', 'user'])
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('user.order-master', compact('orders'));
    }

    public function exportPdf(Order $order)
    {
        $order->load(['user', 'items.product', 'paymentProof.paymentMethod']); 
        $pdf = PDF::loadView('user.order-pdf', compact('order'));
        return $pdf->download('order_'.$order->order_number.'.pdf');
    }
}
