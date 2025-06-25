<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'gambar',
        'bank',
        'nama',
        'no_rekening',
    ];
}
