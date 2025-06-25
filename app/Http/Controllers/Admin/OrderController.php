<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order; 
use PDF;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $originalPerPage = $request->input('perPage', 10);
        $perPage = $originalPerPage === 'all' ? Order::count() : $originalPerPage;

        $query = Order::with(['user', 'paymentProof.paymentMethod', 'items.product']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('order_number', 'like', "%{$search}%");
        }

        $sortOrder = $request->input('sort', 'desc');
        $orders = $query->orderBy('created_at', $sortOrder)->paginate($perPage);

        Order::where('is_seen_by_admin', false)->update(['is_seen_by_admin' => true]);

        return view('admin.order-master', compact('orders', 'perPage', 'originalPerPage'));
    }


    public function approve(Order $order)
    {
        $order->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Order approved successfully.');
    }

    public function deny(Order $order)
    {
        $order->update(['status' => 'denied']);
        return redirect()->back()->with('error', 'Order denied.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:waiting_confirmation,approved,denied',
        ]);
    
        $order->update(['status' => $validated['status']]);
    
        return redirect()->back()->with('success', 'Order status updated.');
    }

    public function exportPdf(Order $order)
    {
        $order->load(['user', 'items.product', 'paymentProof.paymentMethod']); 
        $pdf = PDF::loadView('admin.order-pdf', compact('order'));
        return $pdf->download('order_'.$order->order_number.'.pdf');
    }
}