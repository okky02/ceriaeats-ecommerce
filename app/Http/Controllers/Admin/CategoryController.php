<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $originalPerPage = $request->input('perPage', 10);
        $perPage = $originalPerPage;

        if ($perPage == 'all') {
            $perPage = Category::count();
        }

        $query = Category::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama_kategori', 'like', "%{$search}%");
        }

        $sortOrder = $request->input('sort', 'desc'); 
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }
        $categories = $query->orderBy('created_at', $sortOrder)->paginate($perPage);

        return view('admin.category-master', compact('categories', 'perPage', 'originalPerPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpg,jpeg,png',
        ]);

        try {
            $gambarPath = $request->file('gambar')->store('category', 'public');

            Category::create([
                'nama_kategori' => $request->nama_kategori,
                'gambar' => $gambarPath,
            ]);

            return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error_title', 'Gagal Menambah Kategori')
                ->with('error_message', $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);
    
        try {
            $category = Category::findOrFail($id);
            $category->nama_kategori = $request->nama_kategori;
    
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama
                if ($category->gambar && \Storage::disk('public')->exists($category->gambar)) {
                    \Storage::disk('public')->delete($category->gambar);
                }
    
                // Upload gambar baru
                $gambarPath = $request->file('gambar')->store('category', 'public');
                $category->gambar = $gambarPath;
            }
    
            $category->save();
    
            return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error_title', 'Gagal Update Kategori')
                ->with('error_message', $e->getMessage());
        }
    }     
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            if ($category->gambar && \Storage::disk('public')->exists($category->gambar)) {
                \Storage::disk('public')->delete($category->gambar);
            }

            $category->delete();

            return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error_title', 'Gagal Menghapus Kategori')
                ->with('error_message', $e->getMessage());
        }
    }
}
