@extends('layouts.auth')

@section('content')
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div class="m-b">

                <img src="{{asset('img/logo-login.png')}}"/>

            </div>

			<div class="login-box">
			<h3>{{ __('log_in') }}</h3>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <input id="email" type="email" placeholder="{{ __('email') }}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <input id="password" type="password" placeholder="{{ __('password') }}" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">{{ __('login') }}</button>
    
                <!--<a href="#"><small>Forgot password?</small></a>
                <p class="text-muted text-center"><small>Do not have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="register.html">Create an account</a>-->
            </form>


            </div>
                @if(\App::getLocale() =='pl')
                    <a href="{{ route('setlocale', ['locale' => 'en']) }}"><img alt="image" class="" src="{{asset('img/flags/United-Kingdom.png')}}"></a>
                @else
                    <a href="{{ route('setlocale', ['locale' => 'pl']) }}"><img alt="image" class="" src="{{asset('img/flags/Poland.png')}}"></a>
                @endif

        </div>
    </div>
@endsection
