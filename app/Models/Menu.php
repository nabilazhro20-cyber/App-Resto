<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'tbl_menu';

    protected $fillable = [
        'kode',
        'nama',
    ];

    public function details()
    {
        return $this->hasMany(MenuDetail::class, 'id_menu');
    }
}
