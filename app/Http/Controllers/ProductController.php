<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $products = Product::all();

        return view('content.product.index', compact('products'));
    }

    public function show(Product $product)
    {
        // return view('products.show', compact('product'));
    }

    public function create()
    {
        $data = new Product();
        return view('content.product.create', compact('data'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Jika ada gambar yang diunggah, simpan gambar dan masukkan path-nya ke dalam $validatedData
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $imagePath;
        }

        Product::create($validatedData);
        return redirect()->route('products.index')->with('success', 'Product berhasil di tambahkan');
    }

    public function edit(Product $product)
    {
        return view('content.product.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                \Storage::delete('public/' . $product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Update the product with validated data
        $product->update($validatedData);

        return redirect()->route('products.index')->with('success', 'Product berhasil di update');
    }

    public function destroy($id)
    {
        Product::find($id)->delete();

        return redirect()->route('products.index')->with('success', 'Product berhasil di hapus');
    }
}
