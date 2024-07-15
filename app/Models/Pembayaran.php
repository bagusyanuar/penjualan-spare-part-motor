<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'penjualan_id',
        'angsuran_id',
        'tanggal',
        'bank',
        'atas_nama',
        'bukti',
        'status',
        'keterangan_status',
        'keterangan_pembayaran',
        'snap_token'
    ];
}
