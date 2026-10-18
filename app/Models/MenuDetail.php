<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuDetail extends Model
{
    protected $table = 'tbl_menu_detail';

    protected $fillable = [
        'id_menu',
        'id_product',
        'harga',
    ];

    protected $casts = [
        'id_menu' => 'integer',
        'id_product' => 'integer',
        'harga' => 'integer',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
