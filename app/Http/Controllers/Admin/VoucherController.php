<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $originalPerPage = $request->input('perPage', 10);
        $perPage = $originalPerPage;

        if ($perPage == 'all') {
            $perPage = Voucher::count();
        }

        $query = Voucher::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('voucher_code', 'like', "%{$search}%");
        }

        // Validate Sort Input
        $validSorts = ['asc', 'desc'];
        $sortOrder = in_array($request->input('sort'), $validSorts) ? $request->input('sort') : 'desc';
        
        $query->orderBy('created_at', $sortOrder);

        // Paginate
        $vouchers = $query->paginate($perPage);

        return view('admin.voucher-master', compact('vouchers', 'perPage', 'originalPerPage'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|unique:vouchers',
            'discount_percentage' => 'required|integer|min:1|max:100',
            'expired_at' => 'required|date|after:today',
        ]);

        $data = $request->only('voucher_code', 'discount_percentage', 'expired_at');
        $data['voucher_code'] = strtoupper($data['voucher_code']); 

        Voucher::create($data);

        return back()->with('success', 'Voucher berhasil ditambahkan!');
    }
    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'voucher_code' => 'required|unique:vouchers,voucher_code,' . $voucher->id,
            'discount_percentage' => 'required|integer|min:1|max:100',
            'expired_at' => 'required|date|after:today',
        ]);

        $voucher->update([
            'voucher_code' => strtoupper($request->voucher_code),
            'discount_percentage' => $request->discount_percentage,
            'expired_at' => $request->expired_at,
        ]);

        return back()->with('success', 'Voucher berhasil diperbarui!');
    }
    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return back()->with('success', 'Voucher berhasil dihapus!');
    }
}
