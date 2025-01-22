@extends('layouts.v_template')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Table Product</h2>
                <div class="d-flex flex-row-reverse">
                    <a href="{{ route('products.create') }}" class="menu-link menu-toggle">
                        <button class="btn btn-sm btn-pill btn-outline-primary font-weight-bolder" data-toggle='modal' data-target="#modal-product"><i class="fas fa-plus"></i>add data </button>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table" id="tableProduct">
                            <thead class="font-weight-bold text-center">
                                <tr>
                                    {{-- <th>No.</th> --}}
                                    <th>Product</th>
                                    <th>Name</th>
                                    <th>ingredients</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th style="width:90px;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($products as $p)
                                <tr>
                                    <td> <img src="{{ asset('storage/' . $p->image) }}" alt="peoduct Image" class="img-thumbnail" style="max-width: 80px;"></td>
                                    <td>{{$p->name}}</td>
                                    <td>{!! \Illuminate\Support\Str::words($p->ingredients, 20) !!}</td>
                                    <td>{!! \Illuminate\Support\Str::words($p->description, 20) !!}</td>
                                    <td>{{FormatRupiah($p->price)}}</td>
                                    <td>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <a href="{{ route('products.edit', $p->id) }}" class="menu-link menu-toggle">
                                                <div class="menu-item px-3">
                                                    <button class="btn btn-sm btn-light w-100" data-toggle="modal" data-target="#edit-product">
                                                        Edit
                                                    </button>
                                                </div>
                                            </a>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3" style="margin-top: 5px;">
                                                <button class="btn btn-sm btn-danger w-100" data-toggle="modal" data-target="#delete-product-{{ $p->id }}">
                                                    Delete
                                                </button>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($products as $product)
<div class="modal fade" id="delete-product-{{ $product->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteProductLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="deleteProductLabel{{ $product->id }}">Delete Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <p>Are you sure you want to delete this product?</p>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-danger mr-1">Delete</button>
                        <button type="button" class="btn btn-secondary me-1" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach


@endsection
