<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        .navbar {
            background-color: #343a40;
        }

        .btn-custom {
            border-radius: 12px;
            padding: 8px 16px;
            font-size: 1rem;
            max-width: 150px;
            text-align: center;
        }

        .navbar-brand img {
            border-radius: 50%;
        }

        .navbar-brand,
        .navbar-nav .nav-link {
            color: #fff !important;
        }

        .navbar-nav .nav-link {
            margin-right: 1rem;
        }

        .navbar-nav .nav-link:hover {
            color: #e00000 !important;
        }

        .btn-primary {
            background-color: #e00000;
            border-color: #e00000;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #e00000;
            border-color: #e00000;
        }

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

        .status-process {
            background-color: #ffc107;
        }

        .status-paid {
            background-color: #28a745;
            color: white;
        }

        .status-completed {
            background-color: #17a2b8;
            color: white;
        }

        .status-cancelled {
            background-color: #dc3545;
            color: white;
        }

        .progress {
            height: 5px;
            margin: 10px 0;
            background-color: #e9ecef;
        }

        .order-timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .timeline-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .timeline-icon.active {
            background-color: #28a745;
            color: white;
        }

        .timeline-content {
            flex: 1;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .action-button {
            padding: 6px 15px;
            border-radius: 15px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .action-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {

            .btn-custom {
                padding: 6px 12px;
                font-size: 0.9rem;
                max-width: 120px;
            }
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing') }}">
                <img src="{{ asset('metch/media/bg/rimberio.png') }}" alt="Rimberio Logo" width="40" height="40">
                Rimberio
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto justify-content-end d-flex">
                    <li class="nav-item active font-size-h5">
                        <a class="nav-link" href="{{ route('landing') }}" id="nav-home">Home</a>
                    </li>
                    <li class="nav-item font-size-h5">
                        <a class="nav-link" href="#price" id="nav-price">Price List</a>
                    </li>
                    <li class="nav-item font-size-h5">
                        <a class="nav-link" href="#product" id="nav-product">Product</a>
                    </li>
                    <li class="nav-item font-size-h5">
                        <a class="nav-link" href="{{ route('orders.index') }}">Pesanan Saya</a>
                    </li>
                    <li class="nav-item font-size-h5 p-1">
                        <a class="nav-link btn-info text-white btn-custom" href="{{ route('cart.index') }}" style="border-radius: 6px">
                            <i class="fa fa-shopping-cart"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container my-5">


        <h2 class="mb-4">My Orders</h2>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

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
                    <span class="badge badge-secondary ml-1">
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
                <a class="nav-link {{ $status === 'process' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'process']) }}">
                    Process
                    <span class="badge badge-warning ml-1">
                        {{ $statusCounts['process'] ?? 0 }}
                    </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ $status === 'completed' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'completed']) }}">
                    Completed
                    <span class="badge badge-info ml-1">
                        {{ $statusCounts['completed'] ?? 0 }}
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'cancelled' ? 'active' : '' }}" href="{{ route('orders.index', ['status' => 'cancelled']) }}">
                    Cancelled
                    <span class="badge badge-danger ml-1">
                        {{ $statusCounts['cancelled'] ?? 0 }}
                    </span>
                </a>
            </li>
        </ul>

        @if($orders->isEmpty())
        <div class="text-center py-5 justify-content-center">
            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
            <h4>No orders found</h4>
            <p class="text-muted">
                @if(request()->hasAny(['search', 'sort']))
                No orders match your search criteria.
                @else
                You haven't placed any orders yet.
                @endif
            </p>
            <a href="{{ route('landing') }}" class="btn btn-primary mt-3">
                Start Shopping
            </a>
        </div>
        @else
        <!-- Orders List -->
        @foreach($orders as $order)
        <div class="order-card card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Order #{{ $order->order_id }}</h5>
                    <small class="text-muted">
                        Ordered on {{ $order->created_at->format('d M Y, H:i') }}
                    </small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge badge-status status-{{ $order->status }} mr-3">
                        {{ ucfirst($order->status) }}
                    </span>
                    @if($order->status === 'process')
                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" name="status" value="completed" class="btn btn-success" onclick="return confirm('Are you sure you want to mark this order as Completed?')">
                            <i class="fas fa-check"></i> Mark as Completed
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <!-- Order Progress -->
                @if($order->status !== 'cancelled')
                <div class="progress mb-3">
                    @switch($order->status)
                    @case('pending')
                    <div class="progress-bar bg-priamry" style="width: 25%"></div>
                    @break
                    @case('paid')
                    <div class="progress-bar bg-success" style="width: 50%"></div>
                    @break
                    @case('process')
                    <div class="progress-bar bg-warning" style="width: 75%"></div>
                    @break
                    @case('completed')
                    <div class="progress-bar bg-info" style="width: 100%"></div>
                    @break
                    @endswitch
                </div>
                @endif

                <!-- Order Items -->
                @foreach($order->orderItems as $item)
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="product-image">
                    <div class="ml-3 flex-grow-1">
                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                        <small class="text-muted">
                            {{ $item->quantity }} x {{ formatRupiah($item->price) }}
                        </small>
                    </div>
                    <div class="text-right">
                        <div class="text-muted">Subtotal</div>
                        <strong>{{ formatRupiah($item->quantity * $item->price) }}</strong>
                    </div>
                </div>
                @endforeach

                <hr>

                <!-- Order Summary -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="order-timeline">
                            <div class="timeline-item">
                                <div class="timeline-icon {{ in_array($order->status, ['pending','process', 'paid', 'completed']) ? 'active' : '' }}">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Order Placed</h6>
                                    <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon {{ in_array($order->status, ['paid', 'completed', 'process']) ? 'active' : '' }}">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Payment Completed</h6>
                                    <small class="text-muted">
                                        @if($order->payment_time)
                                        {{ \Carbon\Carbon::parse($order->payment_time)->format('d M Y, H:i') }}
                                        @else
                                        Pending
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon {{ $order->status === 'completed' ? 'active' : '' }}">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Order Completed</h6>
                                    <small class="text-muted">
                                        @if($order->status === 'completed')
                                        {{ $order->updated_at->format('d M Y, H:i') }}
                                        @else
                                        Pending
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <strong>{{ formatRupiah($order->total_amount) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping</span>
                                <strong>Free</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span>Total</span>
                                <strong class="text-primary">{{ formatRupiah($order->total_amount) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
            </div>
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    <script>
        document.getElementById('nav-home').addEventListener('click', function(event) {
            event.preventDefault();
            window.location.href = "{{ route('landing') }}";
        });

        document.getElementById('nav-product').addEventListener('click', function(event) {
            event.preventDefault();
            window.location.href = "{{ route('landing') }}#product";
        });

        document.getElementById('nav-price').addEventListener('click', function(event) {
            event.preventDefault();
            window.location.href = "{{ route('landing') }}#price";
        });

    </script>
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"></script>

</body>
</html>
