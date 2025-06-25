<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\PaymentProof;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = $user->cart()->with('items')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->back()
            ->with('error_title','Failed!!')
            ->with('error_message', 'Keranjang Anda kosong.');
        }

        $subtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);
        $discount = $cart->discount_amount;
        $total = $subtotal - $discount;

        $order = Order::create([
            'user_id' => $user->id,
            'voucher_code' => $cart->voucher_code,
            'subtotal' => $subtotal,
            'discount_percentage' => $cart->discount_percentage,
            'discount_amount' => $discount,
            'total' => $total,
            'status' => 'unpaid',
        ]);   
        
        $order->order_number = 'ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
        $order->save();

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        // Bersihkan keranjang
        $cart->items()->delete();
        $cart->update([
            'discount_percentage' => 0,
            'discount_amount' => 0,
            'voucher_code' => null,
        ]);

        return redirect()->route('user.payment-master', $order->id);
    }
}
