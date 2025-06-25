<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RoleChangeController extends Controller
{
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->email === env('SUPER_ADMIN_EMAIL')) {
            return redirect()->back()->with('error', 'Tidak bisa mengubah role akun utama.');
        }

        if ($user->role === 'user') {
            $user->role = 'admin';
        } else {
            $user->role = 'user';
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Role pengguna berhasil diubah.');
    }
}
