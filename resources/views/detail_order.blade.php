<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rimberio | Order Details</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        .order-timeline {
            position: relative;
            padding-left: 30px;
        }

        .order-timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -34px;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #007bff;
            border: 2px solid #fff;
        }

        .order-details {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
        }

    </style>
</head>
<body>
    <!-- Include your navbar here -->

    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2>Order Details</h2>
                <p class="text-muted">Order #{{ $order->order_number }}</p>
            </div>
            <div class="col-md-6 text-md-right">
                <span class="status-badge status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Order Items</h5>
                        @foreach($order->orderItems as $item)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="mr-3" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                <small class="text-muted">
                                    {{ $item->quantity }} x {{ formatRupiah($item->price) }}
                                </small>
                            </div>
                            <div class="text-right">
                                <strong>{{ formatRupiah($item->quantity * $item->price) }}</strong>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Order Timeline</h5>
                        <div class="order-timeline">
                            <div class="timeline-item">
                                <h6>Order Placed</h6>
                                <p class="text-muted mb-0">
                                    {{ $order->created_at->format('M d, Y H:i') }}
                                </p>
                            </div>
                            @if($order->status == 'paid')
                            <div class="timeline-item">
                                <h6>Payment Completed</h6>
                                <p class="text-muted mb-0">
                                    {{ $order->updated_at->format('M d, Y H:i') }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="order-details">
                    <h5 class="mb-4">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <strong>{{ formatRupiah($order->total_amount) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-0">
                        <span>Total</span>
                        <strong class="text-primary">{{ formatRupiah($order->total_amount) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
