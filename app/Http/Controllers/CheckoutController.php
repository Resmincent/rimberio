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
    public function process(Request $request)
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
                    'name' => substr($item->product->name, 0, 50) // Batasi nama item max 50 karakter
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
                    'finish' => route('orders.index'),
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
                    'status' => 'pending',
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
        $payload = $request->all();
        $orderId = $payload['order_id'];

        // Verifikasi signature key
        $signatureKey = $payload['signature_key'];
        $expectedSignature = hash(
            'sha512',
            $orderId .
                $payload['status_code'] .
                $payload['gross_amount'] .
                env('MIDTRANS_SERVER_KEY')
        );

        if ($signatureKey !== $expectedSignature) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $order = Order::where('order_id', $orderId)->firstOrFail();

        // Update status order berdasarkan transaction_status dari Midtrans
        switch ($payload['transaction_status']) {
            case 'capture':
            case 'settlement':
                $order->status = 'paid';
                break;
            case 'deny':
                $order->status = 'denied';
                break;
            case 'expire':
                $order->status = 'expired';
                break;
            case 'cancel':
                $order->status = 'cancelled';
                break;
        }

        $order->save();

        return response()->json(['message' => 'Webhook handled successfully']);
    }
}
