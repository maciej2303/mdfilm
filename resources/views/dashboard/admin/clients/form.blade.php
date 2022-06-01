@extends('layouts.app')
@section('title', 'Użytkownicy')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>
                        @switch(Route::currentRouteName())
                            @case('clients.create')
                                {{ __('add_client') }}
                                @break
                            @case('clients.edit')
                                {{ __('edit_client') }}
                                @break

                            @default
                            {{ __('show_client') }}
                        @endswitch
                     </h5>
                    <div class="ibox-tools">
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="POST" enctype="multipart/form-data" action="@if( Route::currentRouteName() == 'clients.create' ){{ route('clients.store') }} @else{{ route('clients.update', $client->id) }}@endif">
                        @if( Route::currentRouteName() != 'clients.create' )
                            @method('PUT')
                        @endif
                        @csrf
                        <fieldset @if( Route::currentRouteName() == 'clients.show' ) {{ 'disabled' }} @endif class="row form-row m-b">
                            <div class="col-12 m-b-sm {{$errors->has('name') ? 'has-error' : ''}}">
                                <label>{{ __('name') }}*</label>
                                <input type="text" class="form-control" name="name" value="{{old('name', @$client->name)}}" >
                                @if ($errors->has('name'))
                                <small class="text-danger">{{$errors->first('name')}}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('nip') ? 'has-error' : ''}}">
                                <label>{{ __('nip') }}*</label>
                                <input type="text" class="form-control" name="nip" value="{{old('nip', @$client->nip)}}"  >
                                @if ($errors->has('nip'))
                                <small class="text-danger">{{$errors->first('nip')}}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('address') ? 'has-error' : ''}}">
                                <label>{{ __('address_data') }}</label>
                                <textarea type="text" class="form-control" name="address">{{old('address', @$client->address)}}</textarea>
                                @if ($errors->has('address'))
                                <small class="text-danger">{{$errors->first('address')}}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('billing_emails.*') ? 'has-error' : ''}}">
                                <label>{{ __('email_for_billing') }}</label>
                                <input type="text" class="form-control" name="billing_emails" value="{{old('billing_emails', @$client->imploded_billing_emails)}}">
                                @if ($errors->has('billing_emails.*'))
                                    <div class="error text-danger">
                                        {{$errors->first('billing_emails.*')}}
                                    </div>
                                @else
                                    <small>{{__('you_can_add_a_few_addresses_separating_them_by_;_like_email1@firma.com;email2@klient.pl') }}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('contact_person') ? 'has-error' : ''}}">
                                <label>{{ __('contact_person') }}</label>
                                <input type="text" class="form-control" name="contact_person" value="{{old('contact_person', @$client->contact_person)}}">
                                @if ($errors->has('contact_person'))
                                    <small class="text-danger">{{$errors->first('contact_person')}}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('contact_emails.*') ? 'has-error' : ''}}">
                                <label>{{ __('email_contact') }}</label>
                                <input type="text" class="form-control" name="contact_emails" value="{{old('contact_emails', @$client->imploded_contact_emails)}}">
                                @if ($errors->has('contact_emails.*'))
                                    <div class="error text-danger">
                                        {{$errors->first('contact_emails.*')}}
                                    </div>
                                @else
                                    <small>{{ __('you_can_add_a_few_addresses_separating_them_by_;_like_email1@firma.com;email2@klient.pl') }}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('phone_number') ? 'has-error' : ''}}">
                                <label>{{ __('phone') }}</label>
                                <input type="text" class="form-control" name="phone_number" value="{{old('phone_number', @$client->phone_number)}}">
                                @if ($errors->has('phone_number'))
                                    <small class="text-danger">{{$errors->first('phone_number')}}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('additional_informations') ? 'has-error' : ''}}">
                                <label>{{ __('additional_inforations') }}</label>
                                <textarea type="text" class="form-control" name="additional_informations">{{old('additional_informations', @$client->additional_informations)}}</textarea>
                                @if ($errors->has('additional_informations'))
                                <small class="text-danger">{{$errors->first('additional_informations')}}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('status') ? 'has-error' : ''}}">
                                <label>{{ __('status') }}*</label>
                                <select class="form-control" name="status">
                                    @foreach ($statuses as $status)
                                    <option value="{{$status}}" {{old('status', @$client->status) == $status ? 'selected' : '' }}>{{\App\Enums\ClientStatus::getDescription($status)}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('status'))
                                <small class="text-danger">{{$errors->first('status')}}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('logo') ? 'has-error' : ''}}">
                                @isset($client)
                                @if($client->logo)
                                <label>{{ __('actual_logo') }}</label><br>
								<img src="{{asset($client->logo)}}" class="m-b-sm" style="max-height: 100px"><br>
                                @endif
								<label>{{__('change_logo') }}</label>
                                @else
                                <label>{{ __('logo')}}</label>
                                @endisset
                                <div class="custom-file m-b-xs">
                                    <input id="inputGroupFile01" type="file" class="custom-file-input" name="logo">
                                    <label class="custom-file-label" for="inputGroupFile01"
                                    style="{{$errors->has('logo') ? 'border-color: #ED5565' : ''}}"
                                    >{{ __('chose_file') }}</label>
                                </div>
                                @if ($errors->has('logo'))
                                    <small class="text-danger">{{$errors->first('logo')}}</small>
                                @endif
                            </div>
                        </fieldset>
                        <a href="{{route('clients.index')}}" class="btn btn-default">{{ __('return') }}</a>
                        <button type="submit" class="btn btn-primary {{Route::currentRouteName() == 'clients.show' ? 'd-none' : ''}}">
                            {{ __('save') }}
                        </button>
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
    });
</script>
@endpush
