<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan halaman keranjang belanja
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cartItems = $cart->cartItems;
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart', compact('cartItems', 'total'));
    }

    // Menambahkan produk ke keranjang
    public function addToCart(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getOrCreateCart();
        $product = Product::findOrFail($productId);
        $this->addOrUpdateCartItem($cart, $product, $request->input('quantity'));

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    // Memperbarui kuantitas produk di keranjang
    public function updateQuantity(Request $request, $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->update(['quantity' => $request->input('quantity')]);

        return redirect()->route('cart.index')->with('success', 'Kuantitas produk berhasil diperbarui.');
    }

    // Menghapus produk dari keranjang
    public function removeFromCart($cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    // Mendapatkan atau membuat keranjang baru untuk pengguna saat ini
    private function getOrCreateCart()
    {
        return Auth::user()->cart ?: Cart::create(['user_id' => Auth::id()]);
    }

    // Menambahkan atau memperbarui item keranjang
    private function addOrUpdateCartItem($cart, $product, $quantity)
    {
        $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            $cart->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }
    }
}
