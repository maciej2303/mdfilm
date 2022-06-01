@extends('layouts.app')
@section('title', 'Projekty')


@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>{{ Route::currentRouteName() == 'users.create' ? __('add_user') : __('edit_user') }}
                    </h5>
                    <div class="ibox-tools">

                    </div>
                    @if(isset($user->client) && $user->client->status == \App\Enums\ClientStatus::INACTIVE)
                    <br>
                    <small class="text-danger">{{ __('inactive_user_inactive_client') }}</small>
                    @endif
                </div>

                <div class="ibox-content">
                    <form method="POST"
                        action="@if( Route::currentRouteName() == 'users.create' ){{ route('users.store') }} @else{{ route('users.update', $user->id) }}@endif"
                        enctype="multipart/form-data">
                        @if( Route::currentRouteName() != 'users.create' )
                        @method('PUT')
                        @endif
                        @csrf
                        <div class="row form-row m-b">
                            <div class="col-12 m-b-sm {{$errors->has('email') ? 'has-error' : ''}}">
                                <label>{{ __('email') }}*</label>
                                <input type="text" class="form-control" name="email"
                                    value="{{old('email', @$user->email)}}">
                                @if ($errors->has('email'))
                                <small class="text-danger">{{$errors->first('email')}}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('password') ? 'has-error' : ''}}">
                                <label>{{ __('password') }}*</label>
                                <input type="password" class="form-control" name="password" autocomplete="off" id="password_input">
                                @if ($errors->has('password'))
                                <small class="error text-danger">
                                    {{$errors->first('password')}}
                                </small>
                                @endif
                                @if(!$errors->has('password') && Route::currentRouteName() == 'users.edit')
                                <small>{{ __('let_it_empty_for_not_changing_password') }}</small>
                                @endif
                                <br><input type="checkbox" id="showPassword" value="off"> {{ __('show_password') }}
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('name') ? 'has-error' : ''}}">
                                <label>{{ __('user_name') }}*</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{old('name', @$user->name)}}">
                                @if ($errors->has('name'))
                                <small class="text-danger">{{$errors->first('name')}}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('phone_number') ? 'has-error' : ''}}">
                                <label>{{ __('phone') }}</label>
                                <input type="text" class="form-control" name="phone_number"
                                    value="{{old('phone_number', @$user->phone_number)}}">
                                @if ($errors->has('phone_number'))
                                <small class="text-danger">{{$errors->first('phone_number')}}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('level') ? 'has-error' : ''}}">
                                <label>{{ __('level') }}*</label>
                                <select class="form-control level-select" name="level"
                                    onchange="checkLevel('client-input', this.value)">
                                    @foreach ($levels as $level)
                                    <option value="{{$level}}"
                                        {{old('level', @$user->level) == $level ? 'selected' : ''}}>
                                        {{\App\Enums\UserLevel::getDescription($level)}}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('level'))
                                <small class="text-danger">{{$errors->first('level')}}</small>
                                @endif
                            </div>
                            <div id="client-input"
                                class="col-12 m-b-sm {{$errors->has('client') ? 'has-error' : ''}} d-none">
                                <label>{{ __('client') }}*</label>
                                <select class="form-control client-select" name="client_id" disabled>
                                    @foreach ($clients as $client)
                                    <option value="{{$client->id}}"
                                        {{ (old('client', @$user->client->id) == $client->id ? 'selected' : '' )}}>
                                        {{$client->name}}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('client'))
                                <small class="text-danger">{{$errors->first('client')}}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('status') ? 'has-error' : ''}}">
                                <label>{{ __('status') }}*</label>
                                <select class="form-control" name="status">
                                    @foreach ($statuses as $status)
                                    <option value="{{$status}}"
                                        {{ (old('status', @$user->status) == $status ? 'selected' : '' )}}>
                                        {{\App\Enums\UserStatus::getDescription($status)}}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('status'))
                                <small class="text-danger">{{$errors->first('status')}}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('avatar') ? 'has-error' : ''}}">
                                @isset($user)
                                @if($user->avatar)
                                <label>{{ __('actual_avatar') }}</label><br>
								<img src="{{asset($user->avatar)}}" class="m-b-sm" style="width: 128px"><br>
                                @endif
								<label>{{ __('change_avatar') }}</label>
                                @else
                                <label>{{ __('avatar') }}</label>
                                @endisset
                                <div class="custom-file m-b-xs">
                                    <input id="inputGroupFile01" type="file" class="custom-file-input" name="avatar">
                                    <label class="custom-file-label" for="inputGroupFile01"
                                    style="{{$errors->has('avatar') ? 'border-color: #ED5565' : ''}}"
                                    >Wybierz plik</label>
                                </div>
                                @if ($errors->has('avatar'))
                                    <small class="text-danger">{{$errors->first('avatar')}}</small>
                                @endif
                            </div>

                        </div>
                        <a href="{{route('users.index')}}" class="btn btn-default">{{ __('return') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('save') }}</button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{asset('js/plugins/bs-custom-file/bs-custom-file-input.min.js')}}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        checkLevel('client-input', document.querySelector('.level-select').value);
        bsCustomFileInput.init()

        $('#showPassword').change(function() {
            if($('#showPassword').val() == 'off')
            {
                $('#password_input').prop('type', 'text');
                $('#showPassword').val('on');
            }
            else {
                $('#password_input').prop('type', 'password');
                $('#showPassword').val('off');
            }
        });
    });

    function checkLevel(id, elementValue) {
        if (elementValue == 'Client') {
            document.getElementById(id).classList.remove("d-none")
            document.getElementById(id).classList.add("d-block")
            document.querySelector('.client-select').disabled = false;

        } else {
            document.getElementById(id).classList.remove("d-block")
            document.getElementById(id).classList.add("d-none")
            document.querySelector('.client-select').disabled = true;
        }
    }
</script>
@endpush
