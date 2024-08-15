@extends('layouts.v_template')
@section('content')
<form action="{{ route('users.store') }}" method="POST">
    @csrf
    <div class="row mt-2">
        <div class="col-lg-8 mx-auto mt-2">
            <div class="card-body border-1" style="background-color: #FFFFFF; border-radius: 15px">
                <h2>User Details</h2>
                <p class="text-gray-500" style="margin-left: 10px">Add details for the user.</p>
                <div id="user-container">
                    <div id="user" class="m-2">
                        <label for="name" class="required form-label" style="color: #62717D">User Name</label>
                        <div class="input-group mb-5">
                            <input type="text" class="border-hover-success form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" placeholder="Type Name Here" />
                            @error('name')
                            <small class="invalid-feedback" role="alert">{{ $message }}</small>
                            @enderror
                        </div>

                        <label for="email" class="required form-label" style="color: #62717D">Email</label>
                        <div class="input-group mb-5">
                            <input type="email" class="border-hover-success form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}" placeholder="Type Email Here" />
                            @error('email')
                            <small class="invalid-feedback" role="alert">{{ $message }}</small>
                            @enderror
                        </div>

                        <label for="password" class="required form-label" style="color: #62717D">Password</label>
                        <div class="input-group mb-5">
                            <input type="password" class="border-hover-success form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Type Password Here" />
                            @error('password')
                            <small class="invalid-feedback" role="alert">{{ $message }}</small>
                            @enderror
                        </div>

                        <label for="is_admin" class="required form-label" style="color: #62717D">Role</label>
                        <div class="input-group mb-5">
                            <select name="is_admin" id="is_admin" class="border-hover-success form-control @error('is_admin') is-invalid @enderror">
                                <option value="0" {{ old('is_admin') == '0' ? 'selected' : '' }}>User</option>
                                <option value="1" {{ old('is_admin') == '1' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('is_admin')
                            <small class="invalid-feedback" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end m-4">
                <a href="{{ route('users.index') }}" class="btn btn-sm w-125px mt-5 bg-hover-light" style="color: #31383F; background-color: #FFFFFF; margin-right:8px;">
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
