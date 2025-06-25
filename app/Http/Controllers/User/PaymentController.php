<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\PaymentProof;

class PaymentController extends Controller
{
    public function payment(Order $order)
    {
        $user = Auth::user();

        // Pastikan user hanya bisa akses order miliknya
        if ($order->user_id !== $user->id) {
            abort(403);
        }

        $order->load('items.product');

        $paymentMethods = PaymentMethod::all();

        return view('user.payment-master', [
            'order' => $order,
            'user' => $user,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function confirmPayment(Request $request, Order $order)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string|max:1000',
        ]);        

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $proofPath = $request->file('proof')?->store('payment_proofs', 'public');

        if (!$proofPath) {
            return back()->with('error', 'Gagal upload bukti pembayaran. Coba lagi.');
        }

        PaymentProof::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'payment_method_id' => $request->payment_method_id,
            'image' => $proofPath,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);        

        $order->update([
            'status' => 'waiting_confirmation',
        ]);

        return redirect()->route('user.order.index')->with('success', 'Bukti pembayaran berhasil dikirim.');
    }
}
