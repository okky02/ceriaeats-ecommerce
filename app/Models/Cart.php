<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    // Tambahkan semua kolom yang ingin bisa diisi mass-assignment
    protected $fillable = [
        'user_id',
        'voucher_code',
        'discount_percentage',
        'discount_amount'
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class)->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_code', 'voucher_code');
    } 
}