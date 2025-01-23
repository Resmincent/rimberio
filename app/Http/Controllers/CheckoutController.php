<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        try {
            // 1. Validate cart
            $cart = Auth::user()->cart;
            if ($cart->cartItems->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cart is empty'
                ], 400);
            }

            // 2. Calculate total
            $total = $cart->cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            if ($total <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid total amount'
                ], 400);
            }

            // 3. Prepare Midtrans item details
            $items = $cart->cartItems->map(function ($item) {
                return [
                    'id' => $item->product->id,
                    'price' => (int) $item->product->price,
                    'quantity' => $item->quantity,
                    'name' => substr($item->product->name, 0, 50)
                ];
            })->toArray();

            // 4. Generate unique order ID
            $orderId = 'ORD-' . time() . '-' . Str::random(5);

            // 5. Prepare Midtrans parameters
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

            // 6. Log Midtrans request
            Log::info('Midtrans Request Params', [
                'params' => $params,
                'user_id' => Auth::id()
            ]);

            // 7. Send request to Midtrans
            $serverKey = config('midtrans.server_key');
            if (empty($serverKey)) {
                throw new \Exception('Midtrans server key not configured');
            }

            $auth = base64_encode($serverKey);
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $auth,
            ])->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

            // 8. Validate Midtrans response
            if (!$response->successful()) {
                throw new \Exception('Midtrans Error: ' . $response->body());
            }

            $responseData = $response->json();
            if (!isset($responseData['token'])) {
                throw new \Exception('No token received from Midtrans');
            }

            // 9. Create order transaction
            DB::beginTransaction();
            try {
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_id' => $orderId,
                    'total_amount' => $total,
                    'status' => 'pending',
                    'customer_name' => Auth::user()->name,
                    'customer_email' => Auth::user()->email,
                ]);

                // 10. Save order items
                foreach ($cart->cartItems as $item) {
                    $order->orderItems()->create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price
                    ]);
                }

                // 11. Clear cart
                $cart->cartItems()->delete();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception('Order creation failed: ' . $e->getMessage());
            }

            // 12. Return Midtrans snap token
            return response()->json([
                'status' => 'success',
                'snap_token' => $responseData['token'],
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            Log::error('Checkout Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);

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
            Log::info('Midtrans Webhook Received', $payload);

            // 1. Validate payload parameters
            if (!$this->validatePayloadParameters($payload)) {
                return response()->json(['message' => 'Invalid payload'], 400);
            }

            // 2. Verify signature
            if (!$this->verifySignature($payload)) {
                return response()->json(['message' => 'Invalid signature'], 400);
            }

            $orderId = $payload['order_id'];
            $transactionStatus = $payload['transaction_status'];
            $fraudStatus = $payload['fraud_status'] ?? null;

            // 3. Process order
            DB::beginTransaction();
            try {
                $order = Order::where('order_id', $orderId)->lockForUpdate()->firstOrFail();

                // 4. Determine and update order status
                $newStatus = $this->determineOrderStatus($transactionStatus, $fraudStatus);

                $order->update([
                    'status' => $newStatus,
                    'payment_type' => $payload['payment_type'] ?? null,
                    'transaction_id' => $payload['transaction_id'] ?? null,
                    'payment_time' => $payload['transaction_time'] ?? null,
                    'last_updated_at' => now(),
                    'payment_details' => json_encode($payload)
                ]);

                Log::info('Order Status Updated', [
                    'order_id' => $orderId,
                    'new_status' => $newStatus,
                    'transaction_status' => $transactionStatus
                ]);

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Webhook processed successfully'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Webhook Order Processing Error', [
                    'order_id' => $orderId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json(['message' => 'Order processing failed'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Webhook Processing Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['message' => 'Webhook processing failed'], 500);
        }
    }

    private function validatePayloadParameters($payload)
    {
        $requiredFields = [
            'order_id',
            'transaction_status',
            'status_code',
            'gross_amount',
            'signature_key'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($payload[$field])) {
                Log::error("Missing required payload field: {$field}");
                return false;
            }
        }
        return true;
    }

    private function verifySignature($payload)
    {
        $signatureKey = $payload['signature_key'];
        $expectedSignature = hash(
            'sha512',
            $payload['order_id'] .
                $payload['status_code'] .
                $payload['gross_amount'] .
                config('midtrans.server_key')
        );

        if ($signatureKey !== $expectedSignature) {
            Log::error('Invalid Midtrans Signature', [
                'received' => $signatureKey,
                'expected' => $expectedSignature
            ]);
            return false;
        }

        return true;
    }

    private function determineOrderStatus($transactionStatus, $fraudStatus)
    {
        switch ($transactionStatus) {
            case 'capture':
                return $fraudStatus == 'accept' ? 'paid' : 'cancelled';
            case 'settlement':
                return 'paid';
            case 'deny':
            case 'cancel':
            case 'expire':
            case 'refund':
                return 'cancelled';
            case 'pending':
                return 'pending';
            default:
                Log::warning('Unhandled transaction status', [
                    'status' => $transactionStatus
                ]);
                return 'pending';
        }
    }
}
