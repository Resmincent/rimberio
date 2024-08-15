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
                    <li class="nav-item active font-size-h5">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item font-size-h5">
                        <a class="nav-link" href="#price">Price List</a>
                    </li>
                    <li class="nav-item font-size-h5">
                        <a class="nav-link" href="#product">Product</a>
                    </li>
                    <li class="nav-item font-size-h5">
                        <a class="nav-link" href="#review">Review</a>
                    </li>
                    <li class="nav-item font-size-h5 p-1">
                        <a class="nav-link btn-info text-white" href="{{ route('cart.index') }}" style="border-radius: 6px">
                            <i class="fa fa-shopping-cart"></i>
                        </a>
                    </li>
                    <li class="nav-item font-size-h5 p-1">
                        <a class="nav-link btn-primary text-white" href="{{ route('login') }}" style="border-radius: 12px;">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Jumbotron -->
    <div class="jumbotron jumbotron-fluid mb-0" style="background-image: url('{{ asset('metch/media/bg/wkwkw.jpg') }}');">
        <div class="container text-center">
            <h1 class="display-4" style="color:white">Welcome to Mader Baker</h1>
            <a href="#product" class="btn btn-primary btn-lg mt-3">Lihat Product</a>
        </div>
    </div>

    <!-- Price List Section -->
    <section id="price" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Price List</h2>
            <div class="row d-flex justify-content-center">
                <div class="col-sm-6 col-lg-4 col-md-6 p-2 d-flex justify-content-center">
                    <div class="card p-lg-4 p-md-3 p-sm-4 mb-2 col-sm-12 mx-auto">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name Product</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $p)
                                <tr>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ formatRupiah($p->price) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2">Product Tidak Tersedia.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Section -->
    <section id="product" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Products</h2>
            <div class="row">
                @forelse ($products as $product)
                <div class="col-sm-6 col-lg-4 col-md-6 mb-4 d-flex justify-content-center">
                    <div class="card p-3 mx-auto">
                        <img id="image" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" onerror="this.src='{{ asset('images/placeholder.jpg') }}'" class="img-thumbnail card-img-top border border-2" style="height: 400px; object-fit: cover;">
                        <div class="card-body text-center">
                            <h5 class="card-title font-weight-bold" style="font-size: 24px;">{{ $product->name }}</h5>
                            <p class="card-text font-weight-light">{{ $product->description }}</p>
                            <p class="card-text font-weight-bold">{{ formatRupiah($product->price) }}</p>
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <input type="number" name="quantity" value="1" min="1" class="form-control mb-3" style="width: 125px; margin: 0 auto;">
                                <button type="submit" class="btn btn-primary btn-block">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <p class="text-center">No products available at the moment.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Mader Baker</h5>
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
                <small>&copy; 2024 Mader Baker. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>
</body>

</html>
