<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mader Baker | Product Reviews</title>

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
        }

        .review-card {
            border-radius: 15px;
            border: 1px solid #ddd;
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            background-color: #ffffff;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .review-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }

        .rating {
            color: #FFD700;
            margin-bottom: 0.5rem;
        }

        .rating i {
            margin-right: 0.1rem;
        }

        .review-form {
            border-radius: 15px;
            border: 1px solid #ddd;
            padding: 2rem;
            background-color: #f9f9f9;
            margin-bottom: 2rem;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .back-to-landing {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .back-to-landing .btn {
            background-color: #ff6347;
            color: white;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-size: 1rem;
        }

        .back-to-landing .btn:hover {
            background-color: #e55347;
        }

        .review-form h4 {
            margin-bottom: 1.5rem;
        }

        .review-form .form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .review-form .btn-primary {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

    </style>
</head>

<body>
    <div class="container my-5">
        <div class="back-to-landing">
            <a href="{{ route('landing') }}" class="btn btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Form tambah review -->
                @auth
                <div class="review-form">
                    <h4 class="mb-4">Write a Review</h4>
                    <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="rating">Rating</label>
                            <select name="rating" id="rating" class="form-control">
                                @for ($i = 1; $i <= 5; $i++) <option value="{{ $i }}">{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea name="comment" id="comment" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                </div>
                @endauth

                <!-- Tampilkan review yang ada -->
                <h4 class="mb-4">User Reviews</h4>
                @foreach ($reviews as $review)
                <div class="review-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h5 class="mb-1">{{ $review->user->name }}</h5>
                            <div class="rating">
                                @for ($i = 1; $i <= $review->rating; $i++)
                                    <i class="fas fa-star"></i>
                                    @endfor
                            </div>
                        </div>
                        <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                    </div>
                    <p class="mt-2">{{ $review->comment }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>
</body>

</html>
