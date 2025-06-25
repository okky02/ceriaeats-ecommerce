<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $perPageInput = $request->input('perPage', 8);
        $perPage = ($perPageInput === 'all') ? Product::count() : ((int) $perPageInput ?: 8);

        $query = Product::with(['category', 'reviews']);

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $sort = $request->input('sort', 'desc');
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'desc';
        }

        $products = $query->orderBy('created_at', $sort)->paginate($perPage)->appends($request->all());
        $categories = Category::latest()->get();

        return view('user.shop-master', compact('products', 'categories', 'perPage', 'perPageInput'));
    }


    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        $reviews = $product->reviews()->latest()->get();
        $reviewCount = $reviews->count();
        $averageRating = $reviewCount > 0 ? number_format($reviews->avg('rating'), 1) : 5.0;

        return view('user.product-master', compact('product', 'reviews', 'reviewCount', 'averageRating'));
    }
}