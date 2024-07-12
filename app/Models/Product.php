<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'kategori_id',
        'nama',
        'harga',
        'qty',
        'deskripsi',
        'gambar'
    ];

    public function category()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
