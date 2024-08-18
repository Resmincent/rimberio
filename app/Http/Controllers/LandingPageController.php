<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LandingPageController extends Controller
{
    /**
     * Display the landing page with latest 5 Jenis and Events.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'like', "%{$searchTerm}%");
        }
        $products = $query->latest()->take(10)->get();



        return view('landing_page', compact('products'));
    }
}
