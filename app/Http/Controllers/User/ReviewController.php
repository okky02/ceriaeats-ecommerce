<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $validator = Validator::make($request->all(), [
            'rating2' => 'required|integer|min:1|max:5',
            'review2' => 'nullable|string',
        ],[
            'rating2.required' => 'Rating bintang wajib diisi.',]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error_title', 'Failed!!')
                ->with('error_message', $validator->errors()->first());
        }

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $productId,
            'rating' => $request->rating2,
            'review' => $request->review2,
        ]);

        return redirect()->back()->with('success', 'Review berhasil ditambahkan!');
    }
    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403); 
        }

        $review->delete();

        return redirect()->back()->with('success', 'Review berhasil dihapus.');
    }
}
