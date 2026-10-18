<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MenuController;
use App\Models\Product;
use App\Models\Menu;

Route::get('/', function () {
    return view('dashboard', [
        'jumlahProduk' => Product::count(),
        'jumlahMenu' => Menu::count(),
    ]);
})->name('dashboard');

Route::get('/product', [ProductController::class, 'index'])
    ->name('product.index');
Route::get('/product/add', [ProductController::class, 'create'])
    ->name('product.create');
Route::get('/product/{id}/edit', [ProductController::class, 'edit'])
    ->name('product.edit');

Route::get('/menu', [MenuController::class, 'index'])
    ->name('menu.index');
Route::get('/menu/add', [MenuController::class, 'create'])
    ->name('menu.create');
Route::get('/menu/{id}/edit', [MenuController::class, 'edit'])
    ->name('menu.edit');
