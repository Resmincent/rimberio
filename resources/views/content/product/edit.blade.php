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
                        <div class="row mb-1">
                            <div class="col-lg-6">
                                <input type="file" name="image" class="border-hover-success form-control" id="image">
                            </div>
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

                        <label for="category_id" class="required form-label" style="color: #62717D">Category</label>
                        <div class="input-group mb-5">
                            <select name="category_id" id="category_id" class="border-hover-success form-control @error('category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <small class="invalid-feedback" role="alert">{{ $message }}</small>
                            @enderror
                        </div>

                        <label for="description" class="form-label">Description</label>
                        <div class="input-group mb-5">
                            <textarea class="form-control editor @error('description') is-invalid @enderror" name="description" id="description" rows="5">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                            <small class="invalid-feedback" role="alert">{{ $message }}</small>
                            @enderror
                        </div>

                        <label for="ingredients" class="required form-label" style="color: #62717D">Ingredients</label>
                        <div class="input-group mb-5">
                            <textarea class="form-control editor2 @error('ingredients') is-invalid @enderror" name="ingredients" id="ingredients" rows="5">{{ old('ingredients', $product->ingredients) }}</textarea>
                            @error('ingredients')
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

<script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector('.editor'))
        .catch(error => {
            console.error(error);
        });

    ClassicEditor.create(document.querySelector('.editor2'))
        .catch(error => {
            console.error(error);
        });

</script>
@endsection
