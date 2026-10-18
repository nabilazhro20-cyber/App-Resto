<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'tbl_produk';

    protected $fillable = [
        'kode',
        'name',
        'deskripsi',
        'stok',
        'modal',
    ];

    protected $casts = [
        'stok' => 'integer',
        'modal' => 'integer',
    ];

    public function menuDetails()
    {
        return $this->hasMany(MenuDetail::class, 'id_product');
    }
}
