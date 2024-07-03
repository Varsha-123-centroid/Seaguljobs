@extends('layouts.app')

@section('content')


<div class="container">
  <div class="login-page">
    <div class="form">
     <form method="POST" action="{{ route('login') }}">
     @csrf
      <div class="login-form">
        <div class="row">
            <div class="col-lg-4 p-0">
                <img src="{{asset('admin/img/logos/logo.png')}}" class="navbar-brand-img pt-2 img-fluid" alt="main_logo">
            </div>
            <div class="col-lg-8">
                <input type="text" placeholder="Email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <input id="password" placeholder="password"  type="password" class=" @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <button>Login</button>
            </div>
        </div>   
        
     <div class="row">
         <div class="col-lg-6 pt-2 form-check d-flex">
            <input class="form-checks" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
                Remember Me
            </label>
        </div>
        <div class="col-lg-6 text-end form-check pt-2">
            @if (Route::has('password.request'))
                <a class="btn-link text-right" href="{{ route('password.request') }}">
                    Forgot Your Password
                </a>
            @endif
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
