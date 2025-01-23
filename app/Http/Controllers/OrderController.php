<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->is_admin) {
            $status = $request->get('status', 'all');
            $user = Auth::user();

            $query = Order::with(['orderItems.product', 'user'])
                ->latest();

            if ($status !== 'all') {
                $query->where('status', $status);
            }

            if (!$user->is_admin) {
                $query->where('user_id', $user->id);
            }

            $statusQuery = $user->is_admin ? Order::query() : Order::where('user_id', $user->id);
            $statusCounts = [
                'pending' => (clone $statusQuery)->where('status', 'pending')->count(),
                'paid' => (clone $statusQuery)->where('status', 'paid')->count(),
                'process' => (clone $statusQuery)->where('status', 'process')->count(),
                'completed' => (clone $statusQuery)->where('status', 'completed')->count(),
                'cancelled' => (clone $statusQuery)->where('status', 'cancelled')->count(),
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

        if (!$user->is_admin && $order->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $newStatus = $request->input('status');

        // Define allowed status transitions
        $adminTransitions = [
            'pending' => ['paid', 'cancelled'],
            'paid' => ['process', 'cancelled'],
            'process' => ['completed', 'cancelled']
        ];

        $userTransitions = [
            'process' => ['completed']
        ];

        try {
            // Admin status transitions
            if ($user->is_admin) {
                if (
                    isset($adminTransitions[$order->status]) &&
                    in_array($newStatus, $adminTransitions[$order->status])
                ) {

                    $order->status = $newStatus;
                    $order->save();

                    return redirect()->route('orders.index')
                        ->with('success', "Order #{$order->order_id} status updated to " . ucfirst($newStatus) . ".");
                }
            }

            // User status transitions
            if (!$user->is_admin) {
                if (
                    isset($userTransitions[$order->status]) &&
                    in_array($newStatus, $userTransitions[$order->status])
                ) {

                    $order->status = $newStatus;
                    $order->save();

                    return redirect()->route('orders.index')
                        ->with('success', "Order #{$order->order_id} marked as " . ucfirst($newStatus) . ".");
                }
            }

            // If no valid transition is found
            return redirect()->back()
                ->with('error', 'Invalid status transition for this order.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Order Status Update Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'An error occurred while updating order status.');
        }
    }

    public function destroy($id)
    {
        if (!Auth::user()->is_admin) {
            return redirect()->back()
                ->with('error', 'Unauthorized action. Only administrators can delete orders.');
        }

        try {
            $order = Order::with('orderItems')->findOrFail($id);
            if (in_array($order->status, ['paid', 'process', 'completed'])) {
                return redirect()->back()
                    ->with('error', "Cannot delete order with status {$order->status}.");
            }
            DB::beginTransaction();
            $order->orderItems()->delete();
            $order->delete();
            DB::commit();
            Log::info("Order #{$order->order_id} deleted by user " . Auth::id());

            return redirect()->route('orders.index')
                ->with('success', "Order #{$order->order_id} has been deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Delete Error: ' . $e->getMessage(), [
                'order_id' => $id,
                'user_id' => Auth::id(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while deleting the order. Please try again.');
        }
    }
}
