@extends('layouts.app', ['companyLogo' => @$project->client->logo])
@section('title', 'Projekty')

@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>{{ Route::currentRouteName() == 'project_elements.create' ? __('add_project_elemet') : __('edit_project_element') }}
                    </h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <form method="POST"
                        action="@if( Route::currentRouteName() == 'project_elements.create' ){{ route('project_elements.store') }} @else{{ route('project_elements.update', $projectElement->id) }}@endif">
                        @if( Route::currentRouteName() == 'project_elements.edit' )
                        @method('PUT')
                        @endif
                        @csrf
                        <div class="row form-row m-b">
                            <input type="hidden" name="project_id" value="{{$project->id}}">
                            <div class="col-12 m-b-sm {{$errors->has('name') ? 'has-error' : ''}}">
                                <label>{{ __('name') }}*</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{old('name', @$projectElement->name)}}">
                                @if ($errors->has('name'))
                                <small class="text-danger">{{$errors->first('name')}}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('components') ? 'has-error' : ''}}">
                                <label>{{ __('componetnts_of_element') }}*:</label>
                                @isset($projectElement)
                                    @foreach ($projectElement->components as $component)
                                        @if($component->versions->isNotEmpty())
                                            <input type="hidden" name="components[]" value="{{$component->name}}">
                                        @endif
                                    @endforeach
                                @endisset

                                @foreach ($components as $component)
                                <br>
                                <label>
                                    <input type="checkbox" name="components[]" value="{{$component}}" @isset($projectElement) @if($projectElement->project->simple) disabled @endif @endisset
                                    {{isset($projectElement) ? ($projectElement->components->contains('name', $component) ? ($projectElement->components->where('name', $component)->first()->versions->isEmpty() ? 'checked' : 'disabled checked') : '') : ''}}>
                                    {{__($component)}}
                                </label>
                                @endforeach
                                @if ($errors->has('components'))
                                <br><small class="text-danger">{{$errors->first('components')}}</small>
                                @endif
                            </div>

                        <a href="{{route('projects.show', $project->id)}}" class="btn btn-default mr-1">Powrót</a>
                        <button type="submit" class="btn btn-primary mr-1">{{ __('save') }}</button>
                        @admin()
                            @isset($projectElement)
                                @if($projectElement->project->simple == 0)
                                    <a type="button" class="btn btn-danger tooltip-more delete-warning"
                                    data-id="{{$projectElement->id}}" data-route="{{route('project_elements.destroy')}}"
                                    title="">{{ __('delete') }}</a>
                                @endif
                            @endisset
                        @endadmin
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
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
                    window.location.href=data.route;
                }
            });
        });
});
</script>
@endpush
