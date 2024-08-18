<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mader Baker | Your Cart</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: 600;
        }

        .cart-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 30px;
        }

        .product-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .product-img {
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .btn-primary,
        .btn-success {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .btn-primary:hover,
        .btn-success:hover {
            background-color: #45a049;
            border-color: #45a049;
        }

        .btn-danger {
            background-color: #f44336;
            border-color: #f44336;
        }

        .btn-danger:hover {
            background-color: #da190b;
            border-color: #da190b;
        }

        .cart-summary {
            background-color: #e9ecef;
            border-radius: 10px;
            padding: 20px;
        }

        .progress-checkout {
            height: 5px;
            background-color: #e0e0e0;
            margin-bottom: 30px;
        }

        .progress-checkout .progress-bar {
            background-color: #4CAF50;
        }

        .recommendations {
            margin-top: 50px;
        }

        .back-to-landing {
            margin-top: 20px;
            margin-bottom: 20px;

        }

    </style>
</head>

<body>
    <div class="container cart-container">

        <div class="back-to-landing">
            <a href="{{ route('landing') }}" class="btn btn-sm" style="background-color: #ff6347;">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>

        <h2 class="mb-4">Your Cart</h2>

        <div class="progress progress-checkout">
            <div class="progress-bar" role="progressbar" style="width: 33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        @if($cartItems->isEmpty())
        <p>Your cart is empty.</p>
        @else
        <div class="row">
            <div class="col-md-8">
                @foreach($cartItems as $item)
                <div class="product-card d-flex align-items-center">
                    <img src="{{ asset('storage/' . $item->product->image) }}" class="product-img mr-3" style="height: 80px; object-fit: cover;">
                    <div class="flex-grow-1">
                        <h5>{{ $item->product->name }}</h5>
                        <p class="mb-0">Price: {{ formatRupiah($item->product->price) }}</p>
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="form-inline mt-2">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm mr-2" style="width: 60px;">
                            <button type="submit" class="btn btn-sm btn-primary mr-2"><i class="fas fa-sync-alt"></i> Update</button>
                            <button type="submit" form="remove-form-{{ $item->id }}" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i> Remove</button>
                        </form>
                        <form id="remove-form-{{ $item->id }}" action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                    <h5 class="mb-0 ml-3">{{ formatRupiah($item->product->price * $item->quantity) }}</h5>
                </div>
                @endforeach
            </div>
            <div class="col-md-4">
                <div class="cart-summary">
                    <h4>Cart Summary</h4>
                    <hr>
                    <p class="d-flex justify-content-between"><span>Subtotal:</span> <strong>{{ formatRupiah($total) }}</strong></p>
                    <hr>
                    <h5 class="d-flex justify-content-between"><span>Total:</span> <strong>{{ formatRupiah($total) }}</strong></h5>
                    <a href="{{ route('checkout') }}" class="btn btn-success btn-block mt-3"><i class="fas fa-lock"></i> Proceed to Checkout</a>
                </div>
            </div>
        </div>

        @endif
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>
</body>
</html>
