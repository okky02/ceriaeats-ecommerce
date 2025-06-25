<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $originalPerPage = $request->input('perPage', 10);
        $perPage = $originalPerPage;

        if ($perPage == 'all') {
            $perPage = PaymentMethod::count();
        }

        $query = PaymentMethod::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('bank', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('no_rekening', 'like', "%{$search}%");
        }

        $sortOrder = $request->input('sort', 'desc'); 

        $paymentMethods = $query->orderBy('created_at', $sortOrder)->paginate($perPage);

        return view('admin.payment-master', compact('paymentMethods', 'perPage', 'originalPerPage'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'bank' => 'required|string|max:255',
            'no_rekening' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        try {
            $payment = PaymentMethod::findOrFail($id);

            $payment->nama = $request->nama;
            $payment->bank = $request->bank;
            $payment->no_rekening = $request->no_rekening;

            if ($request->hasFile('gambar')) {
                if ($payment->gambar && \Storage::disk('public')->exists($payment->gambar)) {
                    \Storage::disk('public')->delete($payment->gambar);
                }

                $gambarPath = $request->file('gambar')->store('payment', 'public');
                $payment->gambar = $gambarPath;
            }

            $payment->save();

            return redirect()->back()->with('success', 'Metode pembayaran berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error_title', 'Gagal Mengedit Metode Pembayaran')
                ->with('error_message', $e->getMessage());
        }
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'bank' => 'required|string|max:255',
            'no_rekening' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpg,jpeg,png',
        ]);

        try {
            $gambarPath = $request->file('gambar')->store('payment_methods', 'public');

            PaymentMethod::create([
                'nama' => $request->nama,
                'bank' => $request->bank,
                'no_rekening' => $request->no_rekening,
                'gambar' => $gambarPath,
            ]);

            return redirect()->back()->with('success', 'Metode Pembayaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error_title', 'Gagal Menambah Metode Pembayaran')
                ->with('error_message', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $payment = PaymentMethod::findOrFail($id);

            if ($payment->gambar && \Storage::disk('public')->exists($payment->gambar)) {
                \Storage::disk('public')->delete($payment->gambar);
            }

            $payment->delete();

            return redirect()->back()->with('success', 'Metode pembayaran berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error_title', 'Gagal Menghapus Metode Pembayaran')
                ->with('error_message', $e->getMessage());
        }
    }
}
