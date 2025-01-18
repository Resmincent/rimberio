<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function process()
    {
        try {
            // 1. Validasi cart
            if (Auth::user()->cart->cartItems->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cart is empty'
                ], 400);
            }

            // 2. Ambil data cart user
            $cart = Auth::user()->cart;
            $cartItems = $cart->cartItems;

            // 3. Hitung total
            $total = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            if ($total <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid total amount'
                ], 400);
            }

            // 4. Siapkan item details untuk Midtrans
            $items = [];
            foreach ($cartItems as $item) {
                $items[] = [
                    'id' => $item->product->id,
                    'price' => (int) $item->product->price,
                    'quantity' => $item->quantity,
                    'name' => substr($item->product->name, 0, 50)
                ];
            }

            // 5. Generate order ID unik
            $orderId = 'ORD-' . time() . '-' . Str::random(5);

            // 6. Siapkan parameter untuk Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $total,
                ],
                'item_details' => $items,
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone ?? '',
                ],
                'enabled_payments' => [
                    'gopay',
                    'bank_transfer',
                    'credit_card',
                    'bca_va',
                    'bni_va',
                    'bri_va'
                ],
                'callbacks' => [
                    'finish' => route('orders.index', ['status' => 'paid']),
                    'error' => route('orders.index', ['status' => 'cancelled']),
                ]
            ];

            // 7. Log request ke Midtrans untuk debugging
            \Log::info('Midtrans Request:', [
                'params' => $params,
                'user_id' => Auth::id()
            ]);

            // 8. Kirim request ke Midtrans
            $serverKey = config('midtrans.server_key');
            if (empty($serverKey)) {
                throw new \Exception('Midtrans server key is not configured');
            }

            $auth = base64_encode($serverKey);
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $auth,
            ])->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

            // 9. Log response dari Midtrans
            \Log::info('Midtrans Response:', [
                'response' => $response->json(),
                'status' => $response->status(),
                'order_id' => $orderId
            ]);

            if (!$response->successful()) {
                throw new \Exception('Midtrans Error: ' . $response->body());
            }

            $responseData = $response->json();

            if (!isset($responseData['token'])) {
                throw new \Exception('No token received from Midtrans');
            }

            // 10. Buat order baru
            DB::beginTransaction();
            try {
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_id' => $orderId,
                    'total_amount' => $total,
                    'status' => 'paid',
                    'customer_name' => Auth::user()->name,
                    'customer_email' => Auth::user()->email,
                ]);

                // 11. Simpan detail order
                foreach ($cartItems as $item) {
                    $order->orderItems()->create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price
                    ]);
                }

                // 12. Kosongkan cart setelah order dibuat
                $cartItems->each->delete();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception('Failed to create order: ' . $e->getMessage());
            }

            // 13. Return success response dengan snap token
            return response()->json([
                'status' => 'success',
                'snap_token' => $responseData['token'],
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            // 14. Log error jika terjadi masalah
            \Log::error('Checkout Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);

            // 15. Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Payment process failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function webhook(Request $request)
    {
        try {
            $payload = $request->all();

            Log::info('Midtrans Webhook Payload:', $payload);

            $orderId = $payload['order_id'];
            $transactionStatus = $payload['transaction_status'];
            $fraudStatus = $payload['fraud_status'] ?? null;

            // Verify signature key
            $signatureKey = $payload['signature_key'];
            $expectedSignature = hash(
                'sha512',
                $orderId .
                    $payload['status_code'] .
                    $payload['gross_amount'] .
                    config('midtrans.server_key')
            );

            if ($signatureKey !== $expectedSignature) {
                Log::error('Invalid Midtrans signature', [
                    'received' => $signatureKey,
                    'expected' => $expectedSignature
                ]);
                return response()->json(['message' => 'Invalid signature'], 400);
            }

            DB::beginTransaction();
            try {
                $order = Order::where('order_id', $orderId)->lockForUpdate()->firstOrFail();

                Log::info('Processing order status update', [
                    'order_id' => $orderId,
                    'current_status' => $order->status,
                    'new_transaction_status' => $transactionStatus,
                    'fraud_status' => $fraudStatus
                ]);

                // Update order status based on transaction_status
                switch ($transactionStatus) {
                    case 'capture':
                        // For credit card transaction
                        if ($fraudStatus == 'accept') {
                            $order->status = 'paid';
                        } else {
                            $order->status = 'cancelled';
                        }
                        break;
                    case 'settlement':
                        $order->status = 'paid';
                        break;
                    case 'deny':
                    case 'cancel':
                    case 'expire':
                        $order->status = 'cancelled';
                        break;
                    case 'refund':
                        $order->status = 'cancelled';
                        break;
                    default:
                        $order->status = 'paid';
                        break;
                }

                // Add payment details to order
                $order->payment_type = $payload['payment_type'] ?? null;
                $order->transaction_id = $payload['transaction_id'] ?? null;
                $order->payment_time = $payload['transaction_time'] ?? null;
                $order->last_updated_at = now();

                $order->save();

                Log::info('Order status updated successfully', [
                    'order_id' => $orderId,
                    'new_status' => $order->status
                ]);

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Webhook processed successfully'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error processing webhook', [
                    'order_id' => $orderId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Webhook processing failed'
            ], 500);
        }
    }
}
