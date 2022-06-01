@extends('layouts.app', ['companyLogo' => @$project->client->logo])
@section('title', 'Projekty')

@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>{{ Route::currentRouteName() == 'project_element_component_versions.create' ? __('add_') . __($projectElementComponent->name): __('edit_') . __($projectElementComponent->name) }}
                    </h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <form method="POST"
                        action="@if( Route::currentRouteName() == 'project_element_component_versions.create' ){{ route('project_element_component_versions.store') }} @else{{ route('project_element_component_versions.update', $projectElementComponent->id) }}@endif">
                        @if( Route::currentRouteName() != 'project_element_component_versions.create' )
                        @method('PUT')
                        @endif
                        @csrf
                        <div class="row form-row m-b">
                            <input type="hidden" name="project_element_component_id"
                                value="{{$projectElementComponent->id}}">
                            <input type="hidden" id="version" name="version" value={{$versionNumber}}>
                            <input type="hidden" id="innerVersion" name="innerVersion" value={{$innerVersionNumber}}>
                            <div class="col-12 m-b-sm">
                                <label><input type="radio" name="inner" value="1" checked> {{ __('inside') }}</label>
                                <label><input type="radio" name="inner" value="0"> {{ __('for_client') }}</label>
                            </div>
                           <div class="col-12 m-b-sm {{$errors->has('version') ? 'has-error' : ''}}">
                                <label>{{ __('version') }}*</label>
                                <input type="text" id="versionInput" class="form-control" name="version"
                                    value="{{old('version')}}">
                                @if ($errors->has('version'))
                                <small class="text-danger">{{$errors->first('version')}}</small>
                                @endif
                            </div>

                            <a href="{{route('projects.show', $projectElementComponent->projectElement->project)}}" class="btn btn-default"> {{ __('return') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('save') }}</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function () {
        let version = $('#version').val();
        let innerVersion = $('#innerVersion').val();
        if($('input[name="inner"]:checked').val() == 1){
            $('#versionInput').val(innerVersion)
        } else {
            $('#versionInput').val(version)
        }

        $('#versionInput').on('input',function(e){
            if($('input[name="inner"]:checked').val() == 1){
                innerVersion = this.value;
            } else {
                version = this.value
            }
        });

        $('input[type=radio][name=inner]').change(function() {
            if(this.value == 1) {
                $('#versionInput').val(innerVersion)
            } else {
                $('#versionInput').val(version)
            }

        });
    });

</script>
@endpush
