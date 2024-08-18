@extends('layouts.app')
@section('auth')

<div class="login-signin">
    <div class="mb-5">
        <h3 style="color: #d28137">Sign In To Admin</h3>
        <p class="opacity-60 font-weight-bold" style="color: #d28137">Enter your details to login to your account:</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8 mb-5 @error('email') is-invalid @enderror" type="email" placeholder="Email" name="email" id="email" value="{{ old('email') }}" />
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8 mb-5 @error('password') is-invalid @enderror" type="password" placeholder="Password" name="password" id="password" />
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group text-center mt-10">
            <button type="submit" class="btn btn-pill btn-bg-success font-weight-bold opacity-90 px-15 py-3">Sign In</button>
            <a href="{{ route('register') }}"><button type="button" class="btn btn-pill btn-bg-secondary font-weight-bold opacity-90 px-15 py-3">Sign Up</button></a>
        </div>
    </form>

    <div class="mt-10">


    </div>
</div>
<!--end::Login Sign in form-->
<!--end::Main-->
@endsection
