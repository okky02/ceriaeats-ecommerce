<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class LandingController extends Controller
{
    public function landing(Request $request)
    {
        $products = Product::with('reviews')
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->latest()
            ->paginate(10);

            $categories = Category::latest()->get();

        return view('landing.home-master', compact('products', 'categories'));
    }
}
