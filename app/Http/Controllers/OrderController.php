<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->is_admin) {
            $status = $request->get('status', 'all');

            $query = Order::with(['orderItems.product', 'user'])
                ->latest();

            if ($status !== 'all') {
                $query->where('status', $status);
            }

            $statusCounts = [
                'process' => Order::where('status', 'process')->count(),
                'paid' => Order::where('status', 'paid')->count(),
                'completed' => Order::where('status', 'completed')->count(),
                'cancelled' => Order::where('status', 'cancelled')->count()
            ];

            $orders = $query->paginate(10);

            return view('content.order.index', compact('orders', 'status', 'statusCounts'));
        } else {
            $status = $request->get('status', 'all');

            $query = Order::where('user_id', Auth::id())
                ->with(['orderItems.product'])
                ->latest();

            if ($status !== 'all') {
                $query->where('status', $status);
            }

            $statusCounts = Order::where('user_id', Auth::id())
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $orders = $query->paginate(10);

            return view('checkout', compact('orders', 'status', 'statusCounts'));
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $user = Auth::user();

        // Validasi kepemilikan order untuk user non-admin
        if (!$user->is_admin && $order->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $newStatus = $request->status;

        // Validasi perubahan status berdasarkan role
        if ($user->is_admin) {
            // Admin hanya bisa mengubah dari paid ke process
            if ($order->status === 'paid' && $newStatus === 'process') {
                $order->status = 'process';
                $order->save();
                return redirect()->route('orders.index')
                    ->with('success', 'Order status updated to Processing.');
            } else {
                return redirect()->back()
                    ->with('error', 'Invalid status transition. Admin can only change status from Paid to Process.');
            }
        } else {
            // User hanya bisa mengubah dari process ke completed
            if ($order->status === 'process' && $newStatus === 'completed') {
                $order->status = 'completed';
                $order->save();
                return redirect()->route('orders.index')
                    ->with('success', 'Order has been marked as completed.');
            } else {
                return redirect()->back()
                    ->with('error', 'Invalid status transition. You can only mark Processing orders as Completed.');
            }
        }
    }

    public function destroy($id)
    {
        if (!Auth::user()->is_admin) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order has been deleted successfully.');
    }
}
