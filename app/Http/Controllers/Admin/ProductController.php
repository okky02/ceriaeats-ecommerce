<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $originalPerPage = $request->input('perPage', 10);
        $perPage = $originalPerPage;

        if ($perPage == 'all') {
            $perPage = Product::count(); 
        }

        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama_produk', 'like', "%{$search}%");
        }

        if ($request->filled('category_id') && $request->category_id !== '') {
            $categoryId = $request->input('category_id');
            $query->where('category_id', $categoryId);
        }

        $sortOrder = $request->input('sort', 'desc'); 

        $products = $query->orderBy('created_at', $sortOrder)->paginate($perPage);

        $categories = Category::latest()->get();

        return view('admin.product-master', compact('products', 'perPage', 'originalPerPage', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'gambar' => 'required|image|mimes:jpg,jpeg,png',
        ]);
    
        try {
            $harga = str_replace('.', '', $request->input('harga'));
    
            $gambarPath = $request->file('gambar')->store('products', 'public');
    
            Product::create([
                'nama_produk' => $request->nama_produk,
                'category_id' => $request->category_id,
                'harga' => $harga,
                'deskripsi' => $request->deskripsi,
                'gambar' => $gambarPath,
            ]);
    
            return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error_title', 'Gagal Menambah Produk')
                ->with('error_message', $e->getMessage());
        }
    }   

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id', 
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        try {
            $product = Product::findOrFail($id);
            $product->nama_produk = $request->nama_produk;
            $product->category_id = $request->category_id; 
            $product->harga = $request->harga;
            $product->deskripsi = $request->deskripsi;

            // Jika ada gambar yang diupload
            if ($request->hasFile('gambar')) {
                if ($product->gambar && \Storage::disk('public')->exists($product->gambar)) {
                    \Storage::disk('public')->delete($product->gambar);
                }

                $gambarPath = $request->file('gambar')->store('products', 'public');
                $product->gambar = $gambarPath;
            }

            $product->save();

            return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error_title', 'Gagal Update Produk')
                ->with('error_message', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->gambar && \Storage::disk('public')->exists($product->gambar)) {
                \Storage::disk('public')->delete($product->gambar);
            }

            $product->delete();

            return redirect()->back()->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error_title', 'Gagal Menghapus Produk')
                ->with('error_message', $e->getMessage());
        }
    }
}
