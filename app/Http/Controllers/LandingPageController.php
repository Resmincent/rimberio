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
    public function index()
    {
        $products = Product::latest()->take(10)->get();


        return view('landing_page', compact('products'));
    }
}
