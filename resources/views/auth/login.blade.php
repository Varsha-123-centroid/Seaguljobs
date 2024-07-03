@extends('layouts.app')

@section('content')


<div class="container">
  <div class="login-page">
    <div class="form">
     <form method="POST" action="{{ route('login') }}"> 
     @csrf
      <div class="login-form">
        <div class="row">
            <div class="col-lg-12 p-2">
            <img src="{{asset('admin/img/logo.png')}}" class="navbar-brand-img pt-2 pb-5  img-fluid" alt="main_logo">
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
            
        </div>
        <div class="col-lg-6 text-end form-check pt-2">
            
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
