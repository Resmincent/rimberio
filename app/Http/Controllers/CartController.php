<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class CartController extends Controller
{

    public function index()
    {
        $cart = $this->getOrCreateCart();

        return view('cart.index', [
            'cartItems' => $cart->cartItems,
            'total' => $cart->cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            }),
        ]);
    }


    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getOrCreateCart();
        $product = Product::findOrFail($request->product_id);

        // Periksa apakah produk sudah ada di keranjang
        $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Update jumlah jika sudah ada
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity,
            ]);
        } else {
            // Tambahkan item baru
            $cart->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function updateCartItem(Request $request, $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::findOrFail($cartItemId);

        // Pastikan item ini milik pengguna saat ini
        if ($cartItem->cart->user_id !== Auth::id()) {
            return abort(403, 'Unauthorized action.');
        }

        $cartItem->update([
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('cart.index')->with('success', 'Jumlah item berhasil diperbarui.');
    }


    public function removeCartItem($cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);

        // Pastikan item ini milik pengguna saat ini
        if ($cartItem->cart->user_id !== Auth::id()) {
            return abort(403, 'Unauthorized action.');
        }

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }


    public function checkout()
    {
        $cart = $this->getOrCreateCart();
        $cartItems = $cart->cartItems;

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'status' => 'pending',
        ]);

        foreach ($cartItems as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        // Kosongkan keranjang
        $cart->cartItems()->delete();

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat.');
    }


    private function getOrCreateCart()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        return $cart;
    }
}
