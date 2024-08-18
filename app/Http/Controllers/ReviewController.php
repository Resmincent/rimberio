<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'comment' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Review::create([
            'product_id' => $productId,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return back()->with('success', 'Review added successfully!');
    }

    public function index($productId)
    {
        $product = Product::with('reviews.user')->findOrFail($productId);
        $reviews = $product->reviews()->latest()->get();

        return view('review', compact('product', 'reviews'));
    }
}
