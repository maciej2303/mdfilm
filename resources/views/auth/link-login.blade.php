@extends('layouts.auth')

@section('content')
<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <div class="m-b">

            <img src="{{asset('img/logo-login.png')}}"/>

        </div>
        <div class="login-box">
            <h3>{{__('Zaloguj się') }}</h3>
            <p>{{__('Zaloguj się używając swoich danych logowania.') }}</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="hashedUrl" value="{{$hashedUrl}}">
                <input type="hidden" name="projectElementComponentVersionId" value="{{$projectElementComponentVersionId ?? null}}">
                <div class="form-group">
                    <input id="email" type="email" placeholder="E-mail"
                        class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}" autocomplete="email" autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <input id="password" type="password" placeholder="Hasło"
                        class="form-control @error('password') is-invalid @enderror" name="password"
                        autocomplete="current-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">{{__('Login') }}</button>
            </form>
        </div>

        <div class="login-box m-t">
            <h3>{{ __('you_dont_have_an_account_?') }}</h3>
            <p>{{ __('you_can_watch_project_data_without_having_account_in_our_system_enter_your_name_and_surname_use_this_option_only_if_you_didnt_get_your_own_login_data_for_our_system') }}</p>
            <form method="POST" action="{{ route('login.name') }}">
                @csrf
                <input type="hidden" name="hashedUrl" value="{{$hashedUrl}}">
                <input type="hidden" name="projectElementComponentVersionId" value="{{$projectElementComponentVersionId ?? null}}">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Imię i nazwisko" required="" name="name">
                </div>
                <div class="form-group">
                    <input type="email" placeholder="E-mail"
                        class="form-control @error('emailTemporary') is-invalid @enderror" name="emailTemporary"
                        value="{{ old('email') }}" autocomplete="email" autofocus>

                    @error('emailTemporary')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">{{ __('log_in_witchout_user_account') }}</button>
            </form>
        </div>

    </div>
</div>
@endsection
