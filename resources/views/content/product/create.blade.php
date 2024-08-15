@extends('layouts.v_template')
@section('content')
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row mt-2">
        <div class="col-lg-8 mx-auto mt-2">
            <div class="card-body border-1" style="background-color: #FFFFFF; border-radius: 15px">
                <h2>Product Details</h2>
                <p class="text-gray-500" style="margin-left: 10px">Add details Products.</p>
                <div id="reimburse-container">
                    <div id="reimburse" class="m-2">
                        <div class="row mb-1">
                            <div class="col-lg-6"><input type="file" name="image" class="border-hover-success form-control" id="image" required></div>
                        </div>
                        <label for="project_name" class="required form-label" style="color: #62717D">Product Name</label>
                        <div class="input-group mb-5">
                            <input type="text" class="border-hover-success form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $data->name) }}" placeholder="Type Name Here" />
                            @error('name')
                            <small class="invalid-feedback" role="alert">{{ $message }}</small>
                            @enderror
                        </div>

                        <label for="amount" class="required form-label" style="color: #62717D">Price</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">IDR</span>
                            <input type="number" class="border-hover-success form-control @error('price') is-invalid @enderror" aria-label="price" name="price" value="{{ old('price', $data->price) }}" placeholder="Price" />
                            @error('price')
                            <small class="invalid-feedback" role="alert">{{ $message }}</small>
                            @enderror
                            <span class="input-group-text">.00</span>
                        </div>

                        <label for="description" class="required form-label" style="color: #62717D">Description</label>
                        <div class="input-group mb-5">
                            <textarea type="text" name="description" id="description" rows="4" class="border-hover-success form-control @error('description') is-invalid @enderror" value="{{ old('description', $data->description) }}" placeholder="Description" required></textarea>
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
