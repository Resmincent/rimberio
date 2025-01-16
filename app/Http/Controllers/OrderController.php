<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::with('orderItems.product')
            ->when(!auth()->user()->is_admin, function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        $view = auth()->user()->is_admin ? 'content.order.index' : 'checkout';

        return view($view, compact('orders'));
    }

    public function store(Request $request)
    {
        $cartItems = $request->input('cart');
        $total = 0;

        foreach ($cartItems as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $total += $product->price * $item['quantity'];
            }
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat membuat pesanan.');
        }
    }


    public function show($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        if ($order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        return view('content.order.show', compact('order'));
    }


    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Validasi input status
        $request->validate([
            'status' => 'required|in:pending,processed,shipped,completed,canceled',
        ]);

        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
