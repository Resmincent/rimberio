<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->is_admin) {  // Pastikan kolom is_admin ada di tabel users
            // Query untuk admin - mengambil semua order
            $query = Order::with(['orderItems.product'])
                ->latest();

            // Filter berdasarkan status jika ada
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $orders = $query->paginate(10);

            // Return view admin
            return view('admin.orders.index', compact('orders'));
        } else {
            // Query untuk user biasa - hanya mengambil order miliknya
            $status = $request->get('status', 'all');

            $query = Order::where('user_id', Auth::id())
                ->with(['orderItems.product'])
                ->latest();

            // Filter berdasarkan status
            if ($status !== 'all') {
                $query->where('status', $status);
            }

            $orders = $query->paginate(10);

            // Hitung jumlah order per status untuk badge di tabs
            $statusCounts = Order::where('user_id', Auth::id())
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            // Return view user
            return view('orders.index', compact('orders', 'status', 'statusCounts'));
        }
    }

    public function destroy($id)
    {
        // Pastikan hanya admin yang bisa menghapus order
        if (!Auth::user()->is_admin) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order berhasil dihapus.');
    }
}
