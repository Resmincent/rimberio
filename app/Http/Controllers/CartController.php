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

    public function checkout()
    {
        $cart = $this->getOrCreateCart();
        $cartItems = $cart->cartItems;
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Buat pesan untuk dikirim melalui WhatsApp
        $message = "Halo, saya ingin memesan kue ini apakah masih tersedia?\n";
        $message .= "Nama: \n";
        $message .= "Alamat: " . "\n";
        $message .= "No Hp: " . "\n";
        $message .= "Tanggal Pengiriman: " . "\n";
        foreach ($cartItems as $item) {
            $message .= "Nama Produk: {$item->product->name} - Qty: {$item->quantity}, Harga: Rp " . number_format($item->product->price * $item->quantity, 0, ',', '.') . "\n";
        }
        $message .= "Total: Rp " . number_format($total, 0, ',', '.') . "\n";

        // Encode pesan untuk URL
        $message = urlencode($message);

        // Gantilah 'phone_number' dengan nomor WhatsApp Anda
        $whatsappLink = "https://wa.me/628111168364?text=$message";

        // Redirect ke link WhatsApp
        return redirect($whatsappLink);
    }
}
