@extends('layouts.app', ['companyLogo' => @$project->client->logo])
@section('title', 'Projekty')

@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>{{ __('edit') }} {{ $projectElementComponentVersion->projectElementComponent->name }}
                    </h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <form method="POST" enctype="multipart/form-data"
                        action="{{route('project_element_component_versions.update', $projectElementComponentVersion->id) }}">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="project_element_component_id"
                                value="{{$projectElementComponentVersion->projectElementComponent->id}}">
                        <div class="row form-row m-b">
                            <div class="col-12 m-b-sm">
                                <fieldset disabled>
                                    <label><input type="radio" name="inner" value="1"
                                            {{$projectElementComponentVersion->inner == 1 ? 'checked' : ''}}>
                                        {{ __('inside') }}</label>
                                    <label><input type="radio" name="inner" value="0"
                                            {{$projectElementComponentVersion->inner == 0 ? 'checked' : ''}}> 
                                        {{ __('for_client') }}</label>
                                </fieldset>
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('version') ? 'has-error' : ''}}">
                                <label>{{ __('version') }}*</label>
                                <input type="text" id="versionInput" class="form-control" name="version"
                                    value="{{old('version', $projectElementComponentVersion->version)}}">
                                @if ($errors->has('version'))
                                <small class="text-danger">{{$errors->first('version')}}</small>
                                @endif
                            </div>

                            <div class="col-12 m-b-sm">
                                @if($projectElementComponentVersion->projectElementComponent->name == 'movie'
                                || $projectElementComponentVersion->projectElementComponent->name == 'animation'
                                || $projectElementComponentVersion->projectElementComponent->name == 'recordings')
                                <h3>Edytuj film poprzez link do filmu z serwisu Youtube</h3>
                                <div class="{{$errors->has('link') ? 'has-error' : ''}} pb-2">
                                    <label>{{ __('video_link') }}</label><br>
                                    <small>{{ __('fill_input_only_if_you_want_to_switch_actual_added') }}</small>
                                    <input type="text" class="form-control" name="link" value="{{old('link')}}">
                                    @if ($errors->has('link'))
                                    <small class="text-danger">{{$errors->first('link')}}</small>
                                    @endif
                                </div>
                                @else
                                <div class="{{$errors->has('pdf') ? 'has-error' : ''}}">
                                    <label>{{ __('attachment_pdf') }}</label><br>
                                    <small>{{ __('fill_input_only_if_you_want_to_switch_actual_added') }}</small>
                                    <div class="custom-file m-b-sm">
                                        <input id="inputGroupFile01" type="file" class="custom-file-input" name="pdf">
                                        <label class="custom-file-label"
                                            style="{{$errors->has('pdf') ? 'border-color: #ED5565' : ''}}"
                                            for="inputGroupFile01">{{ __('chose_file') }}</label>
                                    </div>
                                    @if ($errors->has('pdf'))
                                    <small class="text-danger">{{$errors->first('pdf')}}</small>
                                    @endif
                                </div>
                                @endif
                            </div>
                            <div class="w-100">
                                <a href="{{route('project_element_component_versions.show', $projectElementComponentVersion->id)}}"
                                    class="btn btn-default">{{ __('return') }}</a>&nbsp;
                                <button type="submit" class="btn btn-primary">{{ __('save') }}</button>
                                <a type="button" class="btn btn-danger tooltip-more delete-warning float-right"
                                    data-id="{{$projectElementComponentVersion->id}}"
                                    data-route="{{route('project_element_component_versions.destroy')}}"
                                    title="">{{ __('delete') }}</a>
                            </div>
                        </div>
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
    $(document).ready(function () {
        bsCustomFileInput.init()
    });

    $('.delete-warning').click(function () {
        var id = $(this).data('id');
        var route = $(this).data('route');
        swal({
                title: "{!! __('delete_?') !!}",
                text: "{!! __('element_wont_be_recoverable_!') !!}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{!! __('delete') !!}",
                cancelButtonText: "{!! __('do_not_delete') !!}",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function () {
                $.ajax({
                    type: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: route + '?id=' + id,
                    success: function (data) {
                        window.location.href = data.route;
                    }
                });
            });
    });
</script>
@endpush