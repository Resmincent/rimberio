<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Landing Page
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// Authentication Routes
Auth::routes();

// Home Route for Admins
Route::get('/home', [HomeController::class, 'index'])->name('home');

// User Management Routes
Route::resource('users', UsersController::class);

// Product Routes
Route::resource('products', ProductController::class);

// Review Routes
Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/products/{product}/reviews', [ReviewController::class, 'index'])->name('reviews.index');

// Cart Routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/{cartItem}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::patch('/cart/update/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
});

Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// Redirect Non-Admin Users to Landing Page After Login
Route::get('/redirect-user', function () {
    if (Auth::check() && !Auth::user()->is_admin) {
        return redirect()->route('landing');
    }
    return redirect()->route('home');
})->middleware('auth');
