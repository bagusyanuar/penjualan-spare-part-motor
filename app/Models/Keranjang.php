<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjang';

    protected $fillable = [
        'user_id',
        'penjualan_id',
        'product_id',
        'qty',
        'harga',
        'total'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
