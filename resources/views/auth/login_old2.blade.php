@extends('layouts.app')

@section('content')
<div class="container">
<div class="row justify-content-center">
    
    <div class="col-md-8">
                <table class="table table-borderless" style="width:100%;">
                    <tr>
                        <td style="width:13%;">
                            <img src="{{ asset('images/efu-logo.png') }}" style="width:60px;">
                        </td>
                        <td style="padding-top: 30px;">
                            <h2><b>{{ __('IT Inventory And Budgeting System') }}</b></h2>
                        </td>
                    </tr>
                </table>    
            
    </div>
</div>


    <div class="row justify-content-center">
    
        <div class="col-md-7">
                    <table style="width:100%; border: 1px solid black;">
                        <tr>
                            <td style="width:10%; background:skyblue; border-right: 1px solid black; padding: 10px;">
                                <img src="{{ asset('images/login-icon.png') }}" style="width:150px;">
                            </td>
                            <td style="background:aqua; padding: 10px;">
                            @if(session('msg'))
                                
                                <span class="" role="alert">
                                    <strong>{{ session('msg') }}</strong>
                                </span>
                            @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E-Mail Address">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>

                    <div class="form-group">
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

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary" style="padding: 3px 40px;">
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
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <img src="{{ asset('images/inv_sys.png') }}" style="width:250px;">        
        </div>
        <div class="col-md-6" style="text-align:right;">
        <img src="{{ asset('images/budget.png') }}" style="width:250px;">        
        </div>
    </div>
</div>
@endsection
