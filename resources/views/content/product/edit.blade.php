@extends('layouts.v_template')
@section('content')
<form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="row mt-2">
        <div class="col-lg-8 mx-auto mt-2">
            <div class="card-body border-1" style="background-color: #FFFFFF; border-radius: 15px">
                <h2>Product Details</h2>
                <p class="text-gray-500" style="margin-left: 10px">Edit Product Details.</p>
                <div id="reimburse-container">
                    <div id="reimburse" class="m-2">
                        <div class="mb-3">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="img-thumbnail" style="max-width: 200px;">
                            @else
                            <p>No image available</p>
                            @endif
                        </div>
                        <label for="name" class="required form-label" style="color: #62717D">Product Name</label>
                        <div class="input-group mb-5">
                            <input type="text" class="border-hover-success form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $product->name) }}" placeholder="Type Name Here" />
                            @error('name')
                            <small class="invalid-feedback" role="alert">{{ $message }}</small>
                            @enderror
                        </div>

                        <label for="price" class="required form-label" style="color: #62717D">Price</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">IDR</span>
                            <input type="number" class="border-hover-success form-control @error('price') is-invalid @enderror" aria-label="price" name="price" value="{{ old('price', $product->price) }}" placeholder="Price" />
                            @error('price')
                            <small class="invalid-feedback" role="alert">{{ $message }}</small>
                            @enderror
                            <span class="input-group-text">.00</span>
                        </div>

                        <label for="description" class="required form-label" style="color: #62717D">Description</label>
                        <div class="input-group mb-5">
                            <textarea name="description" id="description" rows="4" class="border-hover-success form-control @error('description') is-invalid @enderror" placeholder="Description" required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                            <small class="invalid-feedback" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end m-4">
                <a href="{{ route('products.index') }}" class="btn btn-sm w-125px mt-5 bg-hover-light" style="color: #31383F; background-color: #FFFFFF; margin-right:8px;">
                    Cancel
                </a>
                <button type="submit" class="btn btn-sm w-125px mt-5 bg-hover-success" style="color: #FFFFFF; background-color: #00BD97">
                    Save
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
