@extends('layouts.app')
@section('title', 'Projekty')


@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>{{ __('your_profile') }}
                    </h5>
                    <div class="ibox-tools">

                    </div>
                </div>

                <div class="ibox-content">
                    <form method="POST"
                        action="{{route('profile.update', $user->id)}}"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row form-row m-b">
                            <div class="col-12 m-b-sm {{$errors->has('email') ? 'has-error' : ''}}">
                                <label>{{ __('email') }}*</label>
                                <input type="text" class="form-control" name=""
                                    value="{{old('email', @$user->email)}}" disabled>
                                @if ($errors->has('email'))
                                <small class="text-danger">{{$errors->first('email')}}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('name') ? 'has-error' : ''}}">
                                <label>{{ __('user_name') }}*</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{old('name', @$user->name)}}">
                                @if ($errors->has('name'))
                                <small class="text-danger">{{$errors->first('name')}}</small>
                                @endif
                            </div>
                             <div class="col-12 m-b-sm {{$errors->has('password') ? 'has-error' : ''}}">
                                <label>{{ __('password') }}</label>
                                <input type="password" class="form-control" name="password" autocomplete="off" id="password_input">
                                @if ($errors->has('password'))
                                <small class="error text-danger">
                                    {{$errors->first('password')}}
                                </small>
                                @endif
                                @if(!$errors->has('password'))
                                <small>{{ __('let_it_empty_for_not_changing_password') }}</small>
                                @endif
                                <br><input type="checkbox" id="showPassword" value="off"> {{ __('show_password') }}
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('phone_number') ? 'has-error' : ''}}">
                                <label>{{ __('phone') }}</label>
                                <input type="text" class="form-control" name="phone_number"
                                    value="{{old('phone_number', @$user->phone_number)}}">
                                @if ($errors->has('phone_number'))
                                <small class="text-danger">{{$errors->first('phone_number')}}</small>
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
                                    >{{ __('chose_file') }}</label>
                                </div>
                                @if ($errors->has('avatar'))
                                    <small class="text-danger">{{$errors->first('avatar')}}</small>
                                @endif
                            </div>

                        </div>
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
</script>
@endpush
