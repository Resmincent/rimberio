<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rimberio | Landing Page</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('metch/media/logos/image.png') }}" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f0f5;
            color: #333;
        }

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

        #price,
        #product {
            padding: 2rem 0;
        }

        #price h2,
        #product h2 {
            margin-bottom: 2rem;
            font-weight: 600;
            color: #e00000;
        }

        #price .card,
        #product .card {
            border: none;
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        #price .card:hover,
        #product .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #e00000;
            color: white;
        }

        #product .card-title {
            font-size: 1.25rem;
            color: #333;
        }

        #product .card-text {
            font-size: 0.9rem;
            color: #666;
        }

        #product img {
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        footer {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
            padding: 2rem 0;
        }

        footer h5 {
            margin-bottom: 1rem;
        }

        footer a {
            color: #e00000;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #e00000;
        }

        footer hr {
            border-color: rgba(255, 255, 255, 0.2);
        }

        .menu-section {
            background-color: #f8f9fa;
        }

        .divider {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #dc3545;
        }

        .divider-line {
            height: 2px;
            width: 50px;
            background-color: #dc3545;
        }

        .menu-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .menu-card:hover {
            transform: translateY(-5px);
        }

        .menu-header {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .menu-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 5px;
        }

        .menu-icon {
            font-size: 24px;
        }

        .menu-items {
            padding: 20px;
            max-height: 500px;
            overflow-y: auto;
        }

        .menu-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px dashed #dee2e6;
            transition: background-color 0.3s ease;
        }

        .menu-item:hover {
            background-color: #f8f9fa;
        }

        .menu-item:last-child {
            border-bottom: none;
        }

        .menu-item-info {
            flex: 1;
        }

        .menu-item-title {
            margin: 0;
            color: #343a40;
            font-weight: 600;
        }

        .menu-item-desc {
            margin: 5px 0 0;
            font-size: 0.875rem;
            color: #6c757d;
        }

        .menu-item-price {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .price {
            font-weight: 600;
            color: #dc3545;
            font-size: 1.1rem;
        }

        .add-to-cart-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background-color: #c82333;
            transform: scale(1.1);
        }

        .empty-icon {
            font-size: 48px;
            color: #dee2e6;
        }

        /* Custom Scrollbar */
        .menu-items::-webkit-scrollbar {
            width: 5px;
        }

        .menu-items::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .menu-items::-webkit-scrollbar-thumb {
            background: #dc3545;
            border-radius: 10px;
        }

        .menu-items::-webkit-scrollbar-thumb:hover {
            background: #c82333;
        }

        @media (max-width: 768px) {
            .menu-card {
                margin-bottom: 30px;
            }

            .menu-items {
                max-height: 400px;
            }
        }

    </style>
</head>

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">

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
                        <a class="nav-link" href="{{ route('landing') }}">Home</a>
                    </li>
                    <li class="nav-item font-size-h5">
                        <a class="nav-link" href="#price">Price List</a>
                    </li>
                    <li class="nav-item font-size-h5">
                        <a class="nav-link" href="#product">Product</a>
                    </li>
                    <li class="nav-item font-size-h5">
                        <a class="nav-link" href="{{ route('orders.index') }}">My Order</a>
                    </li>
                    <li class="nav-item font-size-h5 p-1">
                        <a class="nav-link btn-info text-white" href="{{ route('cart.index') }}" style="border-radius: 6px">
                            <i class="fa fa-shopping-cart"></i>
                        </a>
                    </li>
                    @if (!Auth::user())
                    <li class="nav-item font-size-h5 p-1">
                        <a class="nav-link btn-primary text-white" href="{{ route('login') }}" style="border-radius: 12px;">Login</a>
                    </li>
                    @else
                    <li class="nav-item font-size-h5 p-1">
                        <a class="nav-link btn-primary text-white" href="{{ route('logout') }}" style="border-radius: 12px;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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

    <!-- Price List Section -->
    <section id="price" class="menu-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 mb-3">Our Menu</h2>
                <div class="divider">
                    <span class="divider-line"></span>
                    <i class="fas fa-utensils mx-3"></i>
                    <span class="divider-line"></span>
                </div>
            </div>

            <div class="row justify-content-center">
                <!-- Food Menu Card -->
                <div class="col-lg-5 col-md-6 mb-4">
                    <div class="menu-card">
                        <div class="menu-header">
                            <div class="menu-title">
                                <i class="fas fa-hamburger menu-icon"></i>
                                <h3>Makanan</h3>
                            </div>
                        </div>

                        <div class="menu-items">
                            @forelse ($makanan as $p)
                            <div class="menu-item">
                                <div class="menu-item-info">
                                    <h5 class="menu-item-title">{{ $p->name }}</h5>

                                </div>
                                <div class="menu-item-price">
                                    <span class="price">{{ formatRupiah($p->price) }}</span>

                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4">
                                <i class="fas fa-cookie-bite empty-icon"></i>
                                <p class="mt-3">No food items available at the moment.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Beverages Menu Card -->
                <div class="col-lg-5 col-md-6 mb-4">
                    <div class="menu-card">
                        <div class="menu-header">
                            <div class="menu-title">
                                <i class="fas fa-glass-cheers menu-icon"></i>
                                <h3>Minuman</h3>
                            </div>
                        </div>

                        <div class="menu-items">
                            @forelse ($minuman as $p)
                            <div class="menu-item">
                                <div class="menu-item-info">
                                    <h5 class="menu-item-title">{{ $p->name }}</h5>
                                </div>
                                <div class="menu-item-price">
                                    <span class="price">{{ formatRupiah($p->price) }}</span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4">
                                <i class="fas fa-glass-whiskey empty-icon"></i>
                                <p class="mt-3">No beverages available at the moment.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Section -->
    <section id="product" class="py-5">
        <div class="container">
            <h2 class="text-center">Products</h2>
            <div class="row">
                @forelse ($products as $product)
                <div class="col-sm-6 col-lg-4 col-md-6 mb-4 d-flex justify-content-center">
                    <div class="card p-3 mx-auto">
                        <img id="image" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" onerror="this.src='{{ asset('images/placeholder.jpg') }}'" class="img-thumbnail card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body text-center">
                            <h5 class="card-title font-weight-bold">{{ $product->name }}</h5>
                            <p class="card-text font-weight-light">
                                {!! \Illuminate\Support\Str::words($product->description, 20) !!}
                                <a href="#" class="text-primary" data-toggle="modal" data-target="#descriptionModal{{ $product->id }}">Read more</a>
                            </p>

                            <p class="card-text font-weight-bold">{{ formatRupiah($product->price) }}</p>
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <input type="number" name="quantity" value="1" min="1" class="form-control mb-3" style="width: 125px; margin: 0 auto;">
                                <button type="submit" class="btn btn-primary btn-block">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="descriptionModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel{{ $product->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="descriptionModalLabel{{ $product->id }}">{{ $product->name }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" id="myTab{{ $product->id }}" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="description-tab{{ $product->id }}" data-toggle="tab" href="#description{{ $product->id }}" role="tab" aria-controls="description{{ $product->id }}" aria-selected="true">Description</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="ingredients-tab{{ $product->id }}" data-toggle="tab" href="#ingredients{{ $product->id }}" role="tab" aria-controls="ingredients{{ $product->id }}" aria-selected="false">Ingredients</a>
                                    </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content mt-3">
                                    <div class="tab-pane fade show active" id="description{{ $product->id }}" role="tabpanel" aria-labelledby="description-tab{{ $product->id }}">
                                        {!! $product->description !!}
                                    </div>
                                    <div class="tab-pane fade" id="ingredients{{ $product->id }}" role="tabpanel" aria-labelledby="ingredients-tab{{ $product->id }}">
                                        <ul class="list-unstyled">
                                            <li>
                                                <i class="fas fa-check-circle"></i> {!! $product->ingredients !!}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <p class="text-center">No products available at the moment.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination Links -->
            <div class="row">
                <div class="col-12 text-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>


    <!-- Footer -->
    <footer class="text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Rimberio</h5>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#home" class="text-light">Home</a></li>
                        <li><a href="#product" class="text-light">Product</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Follow Us</h5>
                    <a href="#" class="text-light mr-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-light mr-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-light mr-2"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <small>&copy; 2024 Rimberio. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>

</body>

</html>
