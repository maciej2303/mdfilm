@extends('layouts.app')
@section('title', 'Użytkownicy')

@push('css')
<link href="{{asset('css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
<link href="{{asset('css/plugins/colorpicker/bootstrap-colorpicker.min.css')}}" rel="stylesheet">
@endpush


@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>{{ Route::currentRouteName() == 'project_statuses.create' ? __('add_project_status') : __('edit_project_status') }}</h5>
                    <div class="ibox-tools">

                    </div>
                </div>

                <div class="ibox-content">
                    <form method="POST" action="@if( Route::currentRouteName() == 'project_statuses.create' ){{ route('project_statuses.store') }} @else{{ route('project_statuses.update', $projectStatus->id) }}@endif">
                        @if( Route::currentRouteName() != 'project_statuses.create' )
                            @method('PUT')
                        @endif
                        @csrf
                        
                        <div class="row form-row m-b">
                            @foreach($langs as $lang)
                            <div class="col-12 m-b-sm {{$errors->has('name.'.$lang) ? 'has-error' : ''}}">
                                @if($lang == 'pl')
                                   <label>{{ __('name') }}*</label>
                                @else
                                   <label>{{ __('name') }} <span class="text-uppercase">{{$lang}}</span>*</label>
                                @endif
                                
                                <input type="text" class="form-control" name="name[{{ $lang }}]" value="{{ old('name.' . $lang) ? old('name.' . $lang) : @$translations[$projectStatus->id]['name'][$lang] }}" >
                                @if ($errors->has('name.'.$lang))
                                <small class="text-danger">{{$errors->first('name.'.$lang)}}</small>
                                @endif
                            </div>
                            @endforeach
                            <div class="col-12 m-b-sm {{$errors->has('colour') ? 'has-error' : ''}}">
                                <label>{{ __('colour') }}*</label>
                                <div class="input-group colorPicker">
                                    <span class="input-group-addon"><i></i></span><input type="text" autocomplete="off"  name="colour" class="form-control color-input" value="{{old('colour', @$projectStatus->colour)}}" />
                                </div>
                                @if ($errors->has('colour'))
                                <small class="text-danger">{{$errors->first('colour')}}</small>
                                @endif
                            </div>
                        </div>

                        <a href="{{route('project_statuses.index')}}" class="btn btn-default">{{ __('return') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('save') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<!-- Color picker -->
<script src="{{asset('js/plugins/colorpicker/bootstrap-colorpicker.min.js')}}"></script>

<script>
    $('.colorPicker').colorpicker({
            format: 'hex'
        });

    $(".color-input").on('input',(function () {
    }));
</script>
@endpush
