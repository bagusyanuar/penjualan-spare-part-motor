<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiayaPengiriman extends Model
{
    use HasFactory;

    protected $table = 'biaya_pengiriman';

    protected $fillable = [
        'kota',
        'harga'
    ];
}
