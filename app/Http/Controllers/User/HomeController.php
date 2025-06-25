<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $products = Product::with('reviews')
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->latest()
            ->paginate(10);

            $categories = Category::latest()->get();

        return view('user.home-master', compact('products', 'categories'));
    }
}
