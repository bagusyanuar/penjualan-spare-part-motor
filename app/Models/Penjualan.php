<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal',
        'no_penjualan',
        'sub_total',
        'ongkir',
        'bunga',
        'total',
        'dp',
        'sisa',
        'jumlah_angsuran',
        'status',
        'lunas',
        'is_kirim',
        'kota',
        'alamat',
        'kredit'
    ];
}
