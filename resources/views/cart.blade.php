<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rimberio | Shopping Cart</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('metch/media/logos/image.png') }}" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #d28137;
            --secondary-color: #e5533b;
            --dark-color: #343a40;
            --light-color: #f0f0f5;
            --success-color: #4CAF50;
            --danger-color: #f44336;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-color);
            color: #333;
            line-height: 1.6;
        }

        /* Navbar Styles */
        .navbar {
            background-color: var(--dark-color);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
        }

        .navbar-brand img {
            border-radius: 50%;
            border: 2px solid var(--primary-color);
            padding: 2px;
        }

        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        /* Cart Section */
        .page-header {
            background-color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .progress-checkout {
            height: 8px;
            background-color: #e0e0e0;
            margin: 2rem 0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-checkout .progress-bar {
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }

        .product-card {
            background-color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .product-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .cart-summary {
            background-color: white;
            border-radius: 15px;
            padding: 2rem;
            position: sticky;
            top: 2rem;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }

        /* Buttons */
        .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        /* Form Controls */
        .form-control {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 0.5rem;
        }

        /* Footer */
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 3rem 0 1.5rem;
            margin-top: 4rem;
        }

        footer h5 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        footer .social-links a {
            display: inline-block;
            width: 35px;
            height: 35px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 35px;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        footer .social-links a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
        }

        /* Empty Cart State */
        .empty-cart {
            text-align: center;
            padding: 3rem 0;
        }

        .empty-cart i {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .container {
                max-width: 95%;
            }

            .product-card {
                padding: 1.25rem;
            }
        }

        @media (max-width: 991px) {
            .cart-summary {
                margin-top: 2rem;
                position: static;
            }

            .nav-link {
                padding: 0.5rem !important;
            }

            .navbar-brand {
                font-size: 1.25rem;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem 0;
            }

            .page-header h2 {
                font-size: 1.5rem;
            }

            .product-card {
                padding: 1rem;
            }

            .product-card .d-flex {
                flex-direction: column;
                text-align: center;
            }

            .product-img {
                margin-bottom: 1rem;
                width: 120px;
                height: 120px;
            }

            .product-card .text-right {
                text-align: center !important;
                margin-top: 1rem;
                margin-left: 0 !important;
            }

            .product-card .d-flex.align-items-center {
                flex-direction: column;
            }

            .product-card form {
                width: 100%;
                margin: 0.5rem 0;
            }

            .product-card .btn {
                width: 100%;
                margin: 0.5rem 0;
            }

            .form-inline {
                flex-direction: column;
                align-items: stretch !important;
            }

            .form-inline input[type="number"] {
                width: 100% !important;
                margin-bottom: 0.5rem;
            }

            footer {
                text-align: center;
            }

            footer .social-links {
                margin-bottom: 1.5rem;
            }

            .cart-summary {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .navbar {
                padding: 0.5rem;
            }

            .navbar-brand img {
                width: 30px;
                height: 30px;
            }

            .nav-item {
                width: 100%;
                text-align: center;
                padding: 0.25rem 0;
            }

            .nav-link {
                display: block;
                padding: 0.5rem !important;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .product-card h5 {
                font-size: 1.1rem;
            }

            .cart-summary h4 {
                font-size: 1.2rem;
            }

            .progress-checkout {
                height: 6px;
                margin: 1rem 0;
            }

            .empty-cart i {
                font-size: 3rem;
            }

            .empty-cart h3 {
                font-size: 1.3rem;
            }
        }

        /* Additional Responsive Utilities */
        .flex-column-mobile {
            flex-direction: column;
        }

        @media (max-width: 768px) {
            .text-center-mobile {
                text-align: center !important;
            }

            .mt-3-mobile {
                margin-top: 1rem !important;
            }

            .w-100-mobile {
                width: 100% !important;
            }

            .mb-3-mobile {
                margin-bottom: 1rem !important;
            }
        }

        /* Improve Form Controls for Mobile */
        @media (max-width: 768px) {
            .form-control {
                font-size: 16px;
                /* Prevents iOS zoom on focus */
                height: 40px;
            }

            input[type="number"] {
                -moz-appearance: textfield;
                appearance: textfield;
                margin: 0;
            }

            input[type="number"]::-webkit-inner-spin-button,
            input[type="number"]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
        }

        /* Improve Touch Targets for Mobile */
        @media (max-width: 768px) {
            .btn {
                min-height: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .nav-link {
                min-height: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

    </style>
</head>

<body>
    <!-- Navbar with responsive modifications -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('landing') }}">
                <img src="{{ asset('metch/media/bg/icon.jpeg') }}" alt="Rimberio Logo" width="40" height="40" class="mr-2">
                <span>Rimberio</span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <!-- Previous nav items with improved mobile layout -->
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('landing') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#price">Price List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">Keranjang Saya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.index') }}">Pesanan Saya</a>
                    </li>
                    <li class="nav-item p-1">
                        <a class="nav-link btn-info text-white" href="{{ route('cart.index') }}">
                            <i class="fa fa-shopping-cart"></i>
                        </a>
                    </li>
                    @if (!Auth::user())
                    <li class="nav-item p-1">
                        <a class="nav-link btn-primary text-white" href="{{ route('login') }}">Login</a>
                    </li>
                    @else
                    <li class="nav-item p-1">
                        <a class="nav-link btn-primary text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Cart Content with responsive modifications -->
    <div class="container">
        @if($cartItems->isEmpty())
        <div class="empty-cart">
            <i class="fas fa-shopping-cart mb-3"></i>
            <h3>Your cart is empty</h3>
            <p class="text-muted">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('landing') }}" class="btn btn-primary mt-3">Continue Shopping</a>
        </div>
        @else
        <div class="row">
            <div class="col-lg-8 col-md-12">
                @foreach($cartItems as $item)
                <div class="product-card">
                    <div class="d-flex align-items-center flex-column-mobile">
                        <img src="{{ asset('storage/' . $item->product->image) }}" class="product-img mb-3-mobile">
                        <div class="flex-grow-1 text-center-mobile">
                            <h5 class="mb-2">{{ $item->product->name }}</h5>
                            <p class="text-muted mb-2">Unit Price: {{ formatRupiah($item->product->price) }}</p>
                            <div class="d-flex align-items-center justify-content-center flex-column-mobile">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center w-100-mobile flex-column-mobile">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control mb-2-mobile">
                                    <button type="submit" class="btn btn-primary w-100-mobile">
                                        <i class="fas fa-sync-alt"></i> Update
                                    </button>
                                </form>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="ml-2 w-100-mobile mt-2-mobile">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100-mobile">
                                        <i class="fas fa-trash-alt"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="text-right mt-3-mobile">
                            <h5 class="mb-0">{{ formatRupiah($item->product->price * $item->quantity) }}</h5>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="cart-summary">
                    <h4 class="mb-4">Cart Summary</h4>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal</span>
                        <strong>{{ formatRupiah($total) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h5">Total</span>
                        <strong class="h5 text-primary">{{ formatRupiah($total) }}</strong>
                    </div>
                    <a href="{{ route('checkout') }}" class="btn btn-success btn-block">
                        <i class="fas fa-lock"></i> Proceed to Checkout
                    </a>
                    <a href="{{ route('landing') }}" class="btn btn-outline-primary btn-block mt-3">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Responsive Footer -->
    <footer class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>About Rimberio</h5>
                    <p class="text-muted">Your trusted partner for quality products and excellent service.</p>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('landing') }}" class="text-muted">Home</a></li>
                        <li><a href="#product" class="text-muted">Products</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-muted">Cart</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-12">
                    <h5>Connect With Us</h5>
                    <div class="social-links">
                        <a href="#" class="text-light"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center text-muted">
                <small>&copy; 2024 Rimberio. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>
</body>
</html>
