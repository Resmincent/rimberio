<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .navbar {
            background-color: #343a40;
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

        .cart-item {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
        }

        .cart-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .quantity-input {
            width: 80px;
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 5px 10px;
        }

        .cart-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            position: sticky;
            top: 20px;
        }

        .loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                text-align: center;
            }

            .product-image {
                margin-bottom: 15px;
            }

            .cart-summary {
                margin-top: 20px;
                position: static;
            }
        }

    </style>
</head>
<body>
    <!-- Loading indicator -->
    <div class="loading">
        <div class="loading-spinner"></div>
    </div>

    <!-- Navbar -->
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
                        <a class="nav-link btn-info text-white" href="{{ route('cart.index') }}" style="border-radius: 6px">
                            <i class="fa fa-shopping-cart"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4">Shopping Cart</h2>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endif

        @if($cartItems->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h4>Your cart is empty</h4>
            <p class="text-muted">Looks like you haven't added anything to your cart yet.</p>
            <a href="{{ route('landing') }}" class="btn btn-primary mt-3">
                <i class="fas fa-shopping-bag mr-2"></i>Continue Shopping
            </a>
        </div>
        @else
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                @foreach($cartItems as $item)
                <div class="cart-item card mb-3">
                    <div class="card-body d-flex align-items-center">
                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="product-image mr-3">

                        <div class="flex-grow-1">
                            <h5 class="mb-2">{{ $item->product->name }}</h5>
                            <p class="text-muted mb-2">{{ formatRupiah($item->product->price) }}</p>

                            <div class="d-flex align-items-center">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline-block update-quantity-form">
                                    @csrf
                                    @method('PATCH')
                                    <div class="input-group" style="width: 130px;">
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control quantity-input">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-outline-secondary">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline-block ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to remove this item?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="ml-auto text-right">
                            <h5 class="mb-0">{{ formatRupiah($item->product->price * $item->quantity) }}</h5>
                            <small class="text-muted">
                                {{ $item->quantity }} x {{ formatRupiah($item->product->price) }}
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="cart-summary">
                    <h4 class="mb-4">Cart Summary</h4>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                        <strong>{{ formatRupiah($total) }}</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-4">
                        <span>Total</span>
                        <strong class="text-primary h5">{{ formatRupiah($total) }}</strong>
                    </div>

                    <button id="pay-button" class="btn btn-success btn-block mb-3">
                        <i class="fas fa-lock mr-2"></i>Proceed to Payment
                    </button>

                    <a href="{{ route('landing') }}" class="btn btn-outline-primary btn-block">
                        <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>

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

    <script>
        // Show loading indicator
        function showLoading() {
            document.querySelector('.loading').style.display = 'flex';
        }

        // Hide loading indicator
        function hideLoading() {
            document.querySelector('.loading').style.display = 'none';
        }

        // Handle payment button click
        // In your shopping cart view
        document.getElementById('pay-button').addEventListener('click', function(e) {
            e.preventDefault();
            showLoading();

            fetch('{{ route("checkout.process") }}', {
                    method: 'POST'
                    , headers: {
                        'Accept': 'application/json'
                        , 'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                    , credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();

                    if (data.status === 'success') {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                showLoading();
                                // Redirect to orders page after successful payment
                                window.location.href = '{{ route("orders.index", ["status" => "paid"]) }}';
                            }
                            , onPending: function(result) {
                                showLoading();
                                // Redirect to orders page with pending status
                                window.location.href = '{{ route("orders.index", ["status" => "pending"]) }}';
                            }
                            , onError: function(result) {
                                alert('Payment failed! Please try again.');
                                // Redirect to orders page with failed status
                                window.location.href = '{{ route("orders.index", ["status" => "failed"]) }}';
                            }
                            , onClose: function() {
                                alert('You closed the payment window. Your order is still pending.');
                                // Redirect to orders page with pending status
                                window.location.href = '{{ route("orders.index", ["status" => "pending"]) }}';
                            }
                        });
                    } else {
                        alert(data.message || 'Something went wrong! Please try again.');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    alert('Payment failed: ' + error.message);
                });
        });

    </script>
</body>
</html>
