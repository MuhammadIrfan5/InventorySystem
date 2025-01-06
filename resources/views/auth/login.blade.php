@extends('layouts.app')

@section('content')
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<div class="container">
<div class="row" style="margin-top:5rem;">
<div class="col-md-1">
</div>
<div class="col-md-5 justify-content-center">
<img src="{{ asset('images/login-icon2.png') }}" style="width:400px;">
</div>

<div class="col-md-5">

<table class="table table-borderless" style="width:100%;">
                    <tr>

                        <td style="width:10%;">
                            <img src="{{ asset('images/efu-logo.png') }}" style="width:50px;">
                        </td>
                        <td style="padding-top: 22px; padding-left: 0;">
                            <p style="line-height: 0;"><b style="color:rgb(5 126 141);">{{ __('Connect to') }}</b></p>
                            <p style="margin-top: -7px;"><b>{{ __('BUDGETING AND INVENTORY SYSTEM') }}</b></p>
                        </td>
                    </tr>

                    <tr>
                            <td colspan="2" style="padding: 10px;">
                            @if(session('msg'))

                                <span class="" role="alert">
                                    <strong>{{ session('msg') }}</strong>
                                </span>
                            @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1" style="color:white; background:grey;"><i class='fas fa-user-alt'></i></span>
                    </div>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E-Mail Address">
                        @error('email')

                            <span class="invalid-feedback " role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
<br>
<div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1" style="color:white; background:grey;"><i class='fas fa-key'></i></span>
                    </div>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>


                    <!-- <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                    </div> -->
                    <br>

                    <div class="form-group row mb-0">
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-info" style="padding: 3px 30px; background-color:rgb(5 126 141); color:white;">
                                {{ __('Login') }}
                            </button>

                            <!-- @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif -->
                        </div>
                    </div>
                </form>
                            </td>
                        </tr>

                 </table>



</div>
<div class="col-md-1">
</div>
</div>

<p align="center"; style="bottom: 5px; position: absolute; left: 40%;" >
Copyright 2021 all right reserved by EFU LIFE</p>

</div>


@endsection
