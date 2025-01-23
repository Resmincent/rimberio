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
        /* Base styles */
        :root {
            --primary-color: #e00000;
            --dark-bg: #343a40;
            --border-radius: 8px;
        }



        /* Responsive typography */
        html {
            font-size: 16px;
        }

        @media (max-width: 768px) {
            html {
                font-size: 14px;
            }
        }

        /* Navbar styles */
        .navbar {
            background-color: var(--dark-bg);
            padding: 0.5rem 1rem;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            object-fit: cover;
        }

        .navbar-nav {
            gap: 0.5rem;
        }

        /* Cart item styles */
        .cart-item {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
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
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .product-name {
            font-weight: 600;
            color: #333;
        }

        .product-price {
            color: #666;
        }

        .quantity-controls {
            gap: 0.5rem;
        }

        .quantity-input {
            text-align: center;
            font-weight: 500;
        }

        .update-btn,
        .delete-btn {
            padding: 0.375rem 0.75rem;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .total-price {
            color: #2c3e50;
            font-weight: 600;
        }

        /* Cart Summary Styles */
        .cart-summary {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 20px;
        }

        .btn-custom {
            border-radius: 12px;
            padding: 8px 16px;
            font-size: 1rem;
            max-width: 150px;
            text-align: center;
        }


        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .cart-item .card-body {
                padding: 1rem;
            }

            .product-image {
                width: 80px;
                height: 80px;
                margin: 0 auto;
            }

            .quantity-controls {
                flex-wrap: wrap;
                justify-content: center;
            }

            .update-quantity-form {
                width: 100%;
                max-width: 130px;
                margin-bottom: 0.5rem;
            }

            .delete-btn {
                width: 38px;
            }

            .cart-summary {
                position: static;
                margin-top: 1rem;
            }

            .btn-custom {
                padding: 6px 12px;
                font-size: 0.9rem;
                max-width: 120px;
            }
        }

        @media (min-width: 577px) and (max-width: 767px) {
            .quantity-controls {
                justify-content: flex-start;
            }
        }

        /* Touch Device Optimizations */
        @media (hover: none) {

            .update-btn,
            .delete-btn {
                padding: 0.5rem 0.75rem;
            }

            .cart-item:hover {
                transform: none;
            }

            .quantity-input {
                font-size: 16px;
                /* Prevents zoom on iOS */
            }
        }

        /* High DPI screens */
        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            .product-image {
                border: 0.5px solid rgba(0, 0, 0, 0.1);
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
                        <a class="nav-link btn-info text-white btn-custom" href="{{ route('cart.index') }}" style="border-radius: 6px">
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
            <div class="col-lg-8 mb-4">
                @foreach($cartItems as $item)
                <div class="cart-item card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <!-- Product Image -->
                            <div class="col-12 col-sm-3 text-center text-sm-left mb-3 mb-sm-0">
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="product-image">
                            </div>

                            <!-- Product Details -->
                            <div class="col-12 col-sm-9">
                                <div class="row align-items-center">
                                    <!-- Product Info -->
                                    <div class="col-12 col-sm-7 mb-3 mb-sm-0 text-center text-sm-left">
                                        <h5 class="product-name mb-2">{{ $item->product->name }}</h5>
                                        <p class="product-price text-muted mb-2">{{ formatRupiah($item->product->price) }}</p>

                                        <!-- Quantity Controls -->
                                        <div class="quantity-controls d-flex align-items-center justify-content-center justify-content-sm-start">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="update-quantity-form">
                                                @csrf
                                                @method('PATCH')
                                                <div class="input-group" style="max-width: 130px;">
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control quantity-input">
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-outline-secondary update-btn">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>

                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="ml-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger delete-btn" onclick="return confirm('Are you sure you want to remove this item?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Price Summary -->
                                    <div class="col-12 col-sm-5 text-center text-sm-right mt-3 mt-sm-0">
                                        <h5 class="total-price mb-1">{{ formatRupiah($item->product->price * $item->quantity) }}</h5>
                                        <small class="text-muted">
                                            {{ $item->quantity }} x {{ formatRupiah($item->product->price) }}
                                        </small>
                                    </div>
                                </div>
                            </div>
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

                    <hr class="my-3">

                    <div class="d-flex justify-content-between mb-4">
                        <span>Total</span>
                        <strong class="text-primary h5">{{ formatRupiah($total) }}</strong>
                    </div>

                    <div class="cart-actions">
                        <button id="pay-button" class="btn btn-success btn-lg btn-block mb-3">
                            <i class="fas fa-lock mr-2"></i>Proceed to Payment
                        </button>

                        <a href="{{ route('landing') }}" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                                Swal.fire({
                                    icon: 'success'
                                    , title: 'Payment Successful!'
                                    , text: 'Pembayaran Berhasil.'
                                    , confirmButtonText: 'View Orders'
                                    , willClose: () => {
                                        window.location.href = '{{ route("orders.index", ["status" => "paid"]) }}';
                                    }
                                });
                            }
                            , onPending: function(result) {
                                showLoading();
                                Swal.fire({
                                    icon: 'warning'
                                    , title: 'Payment Pending'
                                    , text: 'Pembayaran masih dalam prosess'
                                    , confirmButtonText: 'View Orders'
                                    , willClose: () => {
                                        window.location.href = '{{ route("orders.index", ["status" => "pending"]) }}';
                                    }
                                });
                            }
                            , onError: function(result) {
                                Swal.fire({
                                    icon: 'error'
                                    , title: 'Payment Failed'
                                    , text: 'Coba lagi'
                                    , confirmButtonText: 'View Orders'
                                    , willClose: () => {
                                        window.location.href = '{{ route("orders.index", ["status" => "failed"]) }}';
                                    }
                                });
                            }
                            , onClose: function() {
                                Swal.fire({
                                    icon: 'warning'
                                    , title: 'Payment Window Closed'
                                    , text: 'Pesanan Anda masih tertunda. Harap selesaikan pembayaran nanti.'
                                    , confirmButtonText: 'View Orders'
                                    , willClose: () => {
                                        window.location.href = '{{ route("orders.index", ["status" => "pending"]) }}';
                                    }
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error'
                            , title: 'Error'
                            , text: data.message || 'Something went wrong! Please try again.'
                            , confirmButtonText: 'Close'
                        });
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error'
                        , title: 'Payment Failed'
                        , text: error.message
                        , confirmButtonText: 'Close'
                    });
                });
        });

    </script>
</body>
</html>
