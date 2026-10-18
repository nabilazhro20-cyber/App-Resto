<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        return view('product.index');
    }

    public function create()
    {
        return view('product.add');
    }

    public function edit($id)
    {
        return view('product.edit', ['id' => $id]);
    }

    public function data()
    {
        $products = Product::orderBy('name')->get();
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:30', 'unique:tbl_produk,kode'],
            'name' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'stok' => ['required', 'integer', 'min:0'],
            'modal' => ['required', 'integer', 'min:0'],
        ]);

        $product = Product::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Product berhasil disimpan.',
            'data' => $product,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:30',
                Rule::unique('tbl_produk', 'kode')->ignore($product->id),
            ],
            'name' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'stok' => ['required', 'integer', 'min:0'],
            'modal' => ['required', 'integer', 'min:0'],
        ]);

        $product->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Product berhasil diperbarui.',
            'data' => $product,
        ]);
    }
}
