<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;



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
        $categories = Category::all();
        return view('content.product.create', compact('data', 'categories'));
    }

    public function store(Request $request)
    {


        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'ingredients' => 'required',
            'price' => 'required|numeric|min:0',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id'

        ]);


        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $imagePath;
        }

        Product::create($validatedData);


        return redirect()->route('products.index')->with('success', 'Product berhasil di tambahkan');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('content.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredients' => 'required',
            'category_id' => 'required|exists:categories,id'

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

    public function destroy(String $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product berhasil di hapus');
    }
}
