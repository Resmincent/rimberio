<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
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

Route::resource('categories', CategoryController::class);


// Cart Routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/{cartItem}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::patch('/cart/update/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.update');

    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/payments/webhook', [CheckoutController::class, 'webhook'])->name('payments.webhook');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::middleware(['admin'])->group(function () {
        Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    });
});



// Redirect Non-Admin Users to Landing Page After Login
Route::get('/redirect-user', function () {
    if (Auth::check() && !Auth::user()->is_admin) {
        return redirect()->route('landing');
    }
    return redirect()->route('home');
})->middleware('auth');
