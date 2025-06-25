<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'voucher_code', 
        'discount_percentage', 
        'expired_at'
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];
}
