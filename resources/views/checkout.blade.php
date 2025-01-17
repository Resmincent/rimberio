<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        .order-card {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .nav-tabs .nav-link {
            border-radius: 20px;
            padding: 8px 20px;
            margin-right: 10px;
            color: #6c757d;
        }

        .nav-tabs .nav-link.active {
            background-color: #007bff;
            color: white;
            border: none;
        }

        .badge-status {
            font-size: 0.8rem;
            padding: 8px 12px;
            border-radius: 20px;
        }

        .status-pending {
            background-color: #ffc107;
        }

        .status-paid {
            background-color: #28a745;
            color: white;
        }

        .status-expired {
            background-color: #dc3545;
            color: white;
        }

        .status-cancelled {
            background-color: #6c757d;
            color: white;
        }

    </style>
</head>
<body>
    @include('layouts.navbar')

    <div class="container my-5">
        <h2 class="mb-4">My Orders</h2>

        <!-- Status Tabs -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" href="{{ route('orders.index') }}">
                    All Orders
                    <span class="badge badge-primary ml-1">
                        {{ array_sum($statusCounts) }}
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'pending']) }}">
                    Pending
                    <span class="badge badge-warning ml-1">
                        {{ $statusCounts['pending'] ?? 0 }}
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'paid' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'paid']) }}">
                    Paid
                    <span class="badge badge-success ml-1">
                        {{ $statusCounts['paid'] ?? 0 }}
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'expired' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'expired']) }}">
                    Expired
                    <span class="badge badge-danger ml-1">
                        {{ $statusCounts['expired'] ?? 0 }}
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'cancelled' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'cancelled']) }}">
                    Cancelled
                    <span class="badge badge-secondary ml-1">
                        {{ $statusCounts['cancelled'] ?? 0 }}
                    </span>
                </a>
            </li>
        </ul>

        @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
            <h4>No orders found</h4>
            <p class="text-muted">You haven't placed any orders yet.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                Start Shopping
            </a>
        </div>
        @else
        @foreach($orders as $order)
        <div class="order-card card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Order #{{ $order->order_id }}</h5>
                    <small class="text-muted">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </small>
                </div>
                <span class="badge badge-status status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="card-body">
                @foreach($order->orderItems as $item)
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                    <div class="ml-3">
                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                        <small class="text-muted">
                            {{ $item->quantity }} x {{ formatRupiah($item->price) }}
                        </small>
                    </div>
                </div>
                @endforeach

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Total Amount:</strong>
                        <h5 class="mb-0">{{ formatRupiah($order->total_amount) }}</h5>
                    </div>
                    <div>
                        @if($order->status === 'pending')
                        <button class="btn btn-primary btn-sm pay-button" data-order-id="{{ $order->order_id }}">
                            Pay Now
                        </button>
                        @endif
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        {{ $orders->links() }}
        @endif
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        // Handle payment buttons
        document.querySelectorAll('.pay-button').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.dataset.orderId;

                fetch(`/orders/${orderId}/pay`, {
                        method: 'POST'
                        , headers: {
                            'Content-Type': 'application/json'
                            , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.snap_token) {
                            window.snap.pay(data.snap_token, {
                                onSuccess: function(result) {
                                    window.location.reload();
                                }
                                , onPending: function(result) {
                                    window.location.reload();
                                }
                                , onError: function(result) {
                                    alert('Payment failed! Please try again.');
                                }
                                , onClose: function() {
                                    alert('You closed the payment window without completing the payment.');
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Something went wrong! Please try again.');
                    });
            });
        });

    </script>
</body>
</html>
