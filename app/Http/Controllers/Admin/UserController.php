<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $originalPerPage = $request->input('perPage', 10);
        $perPage = $originalPerPage;

        if ($perPage == 'all') {
            $perPage = \App\Models\User::count();
        }

        $query = \App\Models\User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && $request->role !== '') {
            $role = $request->input('role');
            $query->where('role', $role);
        }

        // Cek apakah user kirim parameter 'sort', kalau tidak default ke 'asc'
        $sortOrder = $request->input('sort');
        if (!$sortOrder) {
            $sortOrder = 'asc'; // default ASC (yang paling lama di atas)
        }

        $users = $query->orderBy('created_at', $sortOrder)->paginate($perPage);

        return view('admin.users-master', compact('users', 'perPage', 'originalPerPage'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() == $user->id) {
            return redirect()->back()->with('error', 'Kamu tidak bisa menghapus akun kamu sendiri.');
        }

        if ($user->email === env('SUPER_ADMIN_EMAIL')) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus akun utama.');
        }
        
        if ($user->profile_photo && \Storage::disk('public')->exists($user->profile_photo)) {
            \Storage::disk('public')->delete($user->profile_photo);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
