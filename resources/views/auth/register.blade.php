 @extends('layouts.app')
 @section('auth')

 <!--begin::Login Sign up form-->
 <div class="login-signin">
     <div class="mb-5">
         <h3 style="color: #d28137">Sign Up</h3>
         <p class="opacity-60" style="color: #d28137">Enter your details to create your account</p>
     </div>

     <form class="form text-center" id="kt_login_signup_form" method="POST" action="{{ route('register') }}">
         @csrf
         <div class="form-group">
             <input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8 @error('name') is-invalid @enderror" type="text" placeholder="Fullname" name="name" value="{{ old('name') }}" />
             @error('name')
             <span class="invalid-feedback" role="alert">
                 <strong>{{ $message }}</strong>
             </span>
             @enderror
         </div>
         <div class="form-group">
             <input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8 @error('email') is-invalid @enderror" type="text" placeholder="Email" name="email" autocomplete="off" value="{{ old('email') }}" />
             @error('email')
             <span class="invalid-feedback" role="alert">
                 <strong>{{ $message }}</strong>
             </span>
             @enderror
         </div>
         <div class="form-group">
             <input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8 @error('password') is-invalid @enderror" type="password" placeholder="Password" name="password" id="password" />
             @error('password')
             <span class="invalid-feedback" role="alert">
                 <strong>{{ $message }}</strong>
             </span>
             @enderror
         </div>
         <div class="form-group">
             <input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8" type="password" placeholder="Confirm Password" name="password_confirmation" id="password-confirm" />
         </div>

         <div class="form-group">
             <button type="submit" class="btn btn-pill btn-bg-success font-weight-bold opacity-90 px-15 py-3 m-2">Sign Up</button>
             <a href={{ route('login') }}><button type="button" class="btn btn-pill btn-bg-secondary font-weight-bold opacity-70 px-15 py-3 m-2">Sign In</button></a>
         </div>
     </form>
 </div>
 <!--end::Login Sign up form-->
 @endsection
