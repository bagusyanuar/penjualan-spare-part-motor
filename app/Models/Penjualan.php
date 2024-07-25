<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

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

    public function pembayaran_status()
    {
        return $this->hasOne(Pembayaran::class, 'penjualan_id')->orderBy('created_at', 'DESC');
    }

    public function pembayaran_token()
    {
        return $this->hasOne(Pembayaran::class, 'penjualan_id')->whereNull('angsuran_id')
            ->orderBy('created_at', 'ASC');
    }
}
