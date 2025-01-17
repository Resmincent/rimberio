@extends('layouts.v_template')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>User Table</h2>
                <div class="d-flex flex-row-reverse">
                    <a href="{{ route('users.create') }}" class="menu-link menu-toggle">
                        <button class="btn btn-sm btn-pill btn-outline-primary font-weight-bolder" data-toggle='modal' data-target="#modal-user"><i class="fas fa-plus"></i>Add User</button>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table" id="tableUser">
                            <thead class="font-weight-bold text-center">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th style="width:90px;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->is_admin ? 'Admin' : 'User' }}</td>
                                    <td>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <a href="{{ route('users.edit', $user->id) }}" class="menu-link menu-toggle">
                                                <div class="menu-item px-3">
                                                    <button class="btn btn-sm btn-light w-100" data-toggle="modal" data-target="#edit-user">
                                                        Edit
                                                    </button>
                                                </div>
                                            </a>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3" style="margin-top: 5px;">
                                                <button class="btn btn-sm btn-danger w-100" data-toggle="modal" data-target="#delete-user">
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

<div class="modal fade" id="delete-user" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="deleteUserLabel">Delete User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <p>Are you sure you want to delete this user?</p>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-danger mr-1">Delete</button>
                        <button type="button" class="btn btn-secondary me-1" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
