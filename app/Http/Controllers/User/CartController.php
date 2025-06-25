<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();

        $cart = Cart::firstOrCreate([
            'user_id' => $user->id
        ]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        $product = Product::find($request->product_id);

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->harga
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function index()
    {
        $user = Auth::user();

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $cart->load('items.product');

        $subtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);

        // Nanti bisa diubah jika kamu ingin menyimpan voucher ke session
        $discountAmount = 0;
        $total = $subtotal - $discountAmount;

        return view('user.cart-master', compact('cart', 'subtotal', 'discountAmount', 'total'));
    }

    public function updateItem(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::findOrFail($id);

        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $cart = $cartItem->cart->load('voucher');

        $cartSubtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);

        // Hitung diskon
        $voucher = $cart->voucher;
        $discountAmount = 0;

        if ($voucher) {
            if ($voucher->discount_percentage > 0) {
                $discountAmount = ($voucher->discount_percentage / 100) * $cartSubtotal;
            }      
        }

        $totalAmount = $cartSubtotal - $discountAmount;

        return response()->json([
            'status' => 'success',
            'message' => 'Quantity updated',
            'subtotal' => number_format($cartItem->quantity * $cartItem->price, 0, ',', '.'),
            'uniqueItemCount' => $cart->items->count(),
            'cartSubtotal' => number_format($cartSubtotal, 0, ',', '.'),
            'discountAmount' => number_format($discountAmount, 0, ',', '.'),
            'totalAmount' => number_format($totalAmount, 0, ',', '.')
        ]);
    }

    public function removeItem($id)
    {
        $item = CartItem::findOrFail($id);

        if ($item->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $item->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
        ]);
    
        $voucher = \App\Models\Voucher::where('voucher_code', $request->voucher_code)
            ->where('expired_at', '>', now())
            ->first();
    
        if (!$voucher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode voucher tidak valid atau sudah kedaluwarsa.',
            ], 404);
        }
    
        $cart = auth()->user()->cart;
    
        if (!$cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Keranjang tidak ditemukan.'
            ], 404);
        }
    
        $subtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);
        $discountAmount = $subtotal * ($voucher->discount_percentage / 100);
        $total = $subtotal - $discountAmount;
    
        // ✅ Simpan ke database
        $cart->discount_percentage = $voucher->discount_percentage;
        $cart->discount_amount = $discountAmount;
        $cart->voucher_code = $voucher->voucher_code;
        $cart->save();
    
        return response()->json([
            'status' => 'success',
            'voucher' => $voucher->voucher_code,
            'discount_percentage' => $voucher->discount_percentage,
            'discount_amount' => number_format($discountAmount, 0, ',', '.'),
            'total' => number_format($total, 0, ',', '.'),
        ]);
    } 
    
    public function resetVoucher(Request $request)
    {
        $cart = auth()->user()->cart;

        if ($cart) {
            $cart->discount_percentage = 0;
            $cart->discount_amount = 0;
            $cart->voucher_code = null; 
            $cart->save();
        }

        return response()->json(['status' => 'success']);
    }
}