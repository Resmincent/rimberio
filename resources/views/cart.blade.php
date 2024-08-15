<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mader Baker | Landing Page</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .navbar-brand {
            font-weight: 600;
        }

        .jumbotron {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/api/placeholder/1200/400');
            background-size: cover;
            background-position: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            height: 400px;
            display: flex;
            align-items: center;
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #007bff;
        }

        .cta-section {
            background-color: #f8f9fa;
            padding: 4rem 0;
        }

        #image {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        #image:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

    </style>
</head>
<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <!-- <a class="navbar-brand" href="#home">
                <img src="{{ asset('assets/dist/img/head.png') }}" alt="Mader Baker Logo" width="30" height="30" class="d-inline-block align-top mr-2">
                Mader Baker
            </a> -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto justify-content-end d-flex">
                    <li class="nav-item active" style="font-size: 24px">
                        <a class="nav-link" href="{{ route('landing') }}">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Your Cart</h2>

        @if($cartItems->isEmpty())
        <p>Your cart is empty.</p>
        @else
        <table class="table table-bordered" style="border-radius: 8px">
            <thead class="thead-light">
                <tr>
                    <th>Product</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr>
                    <td> <img src="{{ asset('storage/' . $item->product->image) }}" class="img-thumbnail" style="height: 80px"></td>
                    <td>{{ $item->product->name }}</td>
                    <td>
                        <form action="{{ route('cart.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1">
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        </form>
                    </td>
                    <td>{{ formatRupiah($item->product->price) }}</td>
                    <td>{{ formatRupiah($item->product->price * $item->quantity) }}</td>

                    <td>
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right">
            <h4>Total: {{ formatRupiah($total) }}</h4>
            <a href="{{ route('checkout') }}" class="btn btn-success mt-3">Proceed to Checkout</a>
        </div>
        @endif
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>
</body>
