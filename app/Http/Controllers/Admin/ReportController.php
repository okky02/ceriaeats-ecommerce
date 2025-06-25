<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $originalPerPage = $request->input('perPage', 10);
        $perPage = $originalPerPage === 'all' ? Order::count() : $originalPerPage;

        // Hanya ambil order yang sudah disetujui (approved)
        $query = Order::with(['user'])->where('status', 'approved');

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                ->orWhereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $sortOrder = $request->input('sort', 'desc');
        $orders = $query->orderBy('created_at', $sortOrder)->paginate($perPage);

        return view('admin.report-master', compact('orders', 'perPage', 'originalPerPage'));
    }

    public function exportPdf(Request $request)
    {
        $query = Order::with(['user'])->where('status', 'approved');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Cegah error dari sort tidak valid
        $sort = in_array($request->input('sort'), ['asc', 'desc']) 
            ? $request->input('sort') 
            : 'desc';

        $reports = $query->orderBy('created_at', $sort)->get();

        $pdf = Pdf::loadView('admin.report-pdf', compact('reports'));
        return $pdf->download('laporan.pdf');
    }
}
