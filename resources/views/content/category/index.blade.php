@extends('layouts.v_template')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container w-xl-550px">
        <div class="card">
            <div class="card-header">
                <h2>Table Category</h2>
                <div class="d-flex flex-row-reverse">
                    <button class="btn btn-sm btn-pill btn-outline-primary font-weight-bolder" data-toggle="modal" data-target="#add-category"><i class="fas fa-plus"></i>add data </button>
                </div>
            </div>
            <div class="card-body">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table" id="tableProduct">
                            <thead class="font-weight-bold text-center">
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($categories as $p)
                                <tr>
                                    <td>{{$p->name}}</td>
                                    <td>
                                        <button class="btn btn-sm btn-light edit-category-btn" data-toggle="modal" data-target="#edit-category" data-id="{{ $p->id }}" data-name="{{ $p->name }}">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-category-btn" data-toggle="modal" data-target="#delete-category" data-id="{{ $p->id }}">
                                            Delete
                                        </button>
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

<!-- Add Category Modal -->
<div class="modal fade" id="add-category" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addCategoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryLabel">Add New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mr-1">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="edit-category" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editCategoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryLabel">Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit_name">Category Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mr-1">Update</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="delete-category" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="deleteCategoryLabel">Delete Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="deleteCategoryForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <p>Are you sure you want to delete this category?</p>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-danger mr-1">Delete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Edit Category Modal Handler
        $('.edit-category-btn').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            $('#editCategoryForm').attr('action', `/categories/${id}`);
            $('#edit_name').val(name);
        });

        // Delete Category Modal Handler
        $('.delete-category-btn').on('click', function() {
            const id = $(this).data('id');
            $('#deleteCategoryForm').attr('action', `/categories/${id}`);
        });
    });

</script>
@endpush

@endsection
