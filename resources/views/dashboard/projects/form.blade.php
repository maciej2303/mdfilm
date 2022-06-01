@extends('layouts.app')
@section('title', 'Projekty')
@push('css')
<style>
    .select2-container .select2-results__option.optInvisible {
        display: none;
    }

    .select2-results__options .select2-results__option[aria-disabled=true] {
        display: none;
    }

</style>
@endpush

@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">

            <div class="ibox ">
                <div class="ibox-title">
                    <h5>{{ Route::currentRouteName() == 'projects.create' ? __('add_project') : __('edit_project') }}</h5>
                    <div class="ibox-tools">

                    </div>
                </div>
                <div class="ibox-content">
                    <form method="POST"
                        action="@if( Route::currentRouteName() == 'projects.create' ){{ route('projects.store') }} @else{{ route('projects.update', $project->id) }}@endif">
                        @if( Route::currentRouteName() != 'projects.create' )
                        @method('PUT')
                        @endif
                        @csrf
                        <fieldset id="form-fieldset" @if( Route::currentRouteName()=='projects.show' ) {{ 'disabled' }} @endif>
                            <input type="hidden" name="editFromShow" value="{{@$editFromShow}}">
                            <div class="row form-row m-b">
                                <div class="col-12 m-b-sm {{$errors->has('name') ? 'has-error' : ''}}">
                                    <label>{{ __('name') }}*</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{old('name', @$project->name)}}">
                                    @if ($errors->has('name'))
                                    <small class="text-danger">{{$errors->first('name')}}</small>
                                    @endif
                                </div>
                                <div class="col-12 m-b-sm {{$errors->has('name') ? 'has-error' : ''}}" >
                                    <label>{{ __('project_type') }}*</label><br>
                                    <fieldset id="simple" @if( Route::currentRouteName()=='projects.edit' && isset($project) && $project->simple == "0" ) {{ 'disabled' }} @endif>
                                    <input type="radio" name="simple" value="1" @if((Route::currentRouteName()=='projects.create' &&  old('simple', @$project->simple) === null ) || old('simple', @$project->simple) == "1" ) {{ 'checked' }} @endif> {{ __('simple_project') }}<br>
                                    <input type="radio" name="simple" value="0 "@if( old('simple', @$project->simple) == "0") {{ 'checked' }} @endif> {{ __('standard_project') }}
                                    </fieldset>
                                    @if ($errors->has('simple'))
                                    <small class="text-danger">{{$errors->first('name')}}</small>
                                    @endif
                                </div>
                                <div class="col-12 m-b-sm {{$errors->has('client_id') ? 'has-error' : ''}}">
                                    <label>{{ __('client') }}*</label>
                                    <select class="form-control select2-clients" name="client_id">
                                        @foreach ($clients as $client)
                                        <option value="{{$client->id}}"
                                            {{old('client_id', @$project->client_id) == $client->id ? 'selected' : '' }}>
                                            {{$client->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('client_id'))
                                    <small class="text-danger">{{$errors->first('client_id')}}</small>
                                    @endif
                                </div>
                                <div class="col-12 m-b-sm {{$errors->has('contact_persons') ? 'has-error' : ''}}">
                                    <label>{{ __('decision_makers') }}</label>
                                    <select class="form-control contacts-select" name="contact_persons[]"
                                        multiple="multiple">
                                        @foreach ($contacts as $contact)
                                        <option value="{{$contact->id}}" data-client="{{$contact->client_id}}"
                                            @if(old('contact_persons'))
                                            {{ in_array($contact->id, old('contact_persons')) ? 'selected' : '' }}
                                            @endif @if(isset($project))
                                            {{ $project->contactPersons->contains($contact->id) ? 'selected' : '' }}
                                            @endif>
                                            {{$contact->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('contact_persons'))
                                    <small class="text-danger">{{$errors->first('contact_persons')}}</small>
                                    @endif
                                </div>

                                <div class="col-12 m-b-sm {{$errors->has('partners') ? 'has-error' : ''}}">
                                    <label>{{ __('project_partners') }}</label>
                                    <select class="form-control persons-select" name="partners[]" multiple="multiple">
                                        @foreach ($partners as $partner)
                                        <option value="{{$partner->id}}" @if(old('partners'))
                                            {{ in_array($partner->id, old('partners')) ? 'selected' : '' }} @endif
                                            @if(isset($project))
                                            {{ $project->partners->contains($partner->id) ? 'selected' : '' }} @endif>
                                            {{$partner->name}} ({{$partner->client->name}})
                                        </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('partners'))
                                    <small class="text-danger">{{$errors->first('partners')}}</small>
                                    @endif
                                </div>

                                <div class="col-12 m-b-sm {{$errors->has('project_description') ? 'has-error' : ''}}">
                                    <label>{{ __('project_description') }}</label>
                                    <textarea type="text" class="form-control"
                                        name="project_description">{{old('project_description', @$project->project_description)}}</textarea>
                                    @if ($errors->has('project_description'))
                                    <small class="text-danger">{{$errors->first('project_description')}}</small>
                                    @endif
                                </div>
                                <div class="col-12 m-b-sm {{$errors->has('managers') ? 'has-error' : ''}}">
                                    <label>{{ __('project_menager') }}</label>
                                    <select class="form-control persons-select" name="managers[]" multiple="multiple">
                                        @foreach ($workers as $worker)
                                        <option value="{{$worker->id}}" @if(old('managers'))
                                            {{ in_array($worker->id, old('managers')) ? 'selected' : '' }} @endif
                                            @if(isset($project))
                                            {{ $project->managers->contains($worker->id) ? 'selected' : '' }} @endif>
                                            {{$worker->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('managers'))
                                    <small class="text-danger">{{$errors->first('managers')}}</small>
                                    @endif
                                </div>
                                <div class="col-12 m-b-sm {{$errors->has('team_members') ? 'has-error' : ''}}">
                                    <label>{{ __('team') }}</label>
                                    <select class="form-control persons-select" name="team_members[]"
                                        multiple="multiple">
                                        @foreach ($workers as $worker)
                                        <option value="{{$worker->id}}" @if(old('team_members'))
                                            {{ in_array($worker->id, old('team_members')) ? 'selected' : '' }} @endif
                                            @if(isset($project))
                                            {{ $project->teamMembers->contains($worker->id) ? 'selected' : '' }} @endif>
                                            {{$worker->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('team_members'))
                                    <small class="text-danger">{{$errors->first('team_members')}}</small>
                                    @endif
                                </div>

                                <div class="col-12 m-b-sm {{$errors->has('term') ? 'has-error' : ''}}">
                                <label>{{ __('term') }}*</label>
                                <div class=" input-group date" id="datePickerProject">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
                                        type="text" name="term" class="form-control" id="term"
                                        value="{{old('term', @$project->term)}}">
                                </div>
                                @if ($errors->has('term'))
                                <small class="text-danger">{{$errors->first('term')}}</small>
                                @endif
                            </div>
                            <div class="col-12 m-b-sm {{$errors->has('project_status_id') ? 'has-error' : ''}}">
                                <label>{{ __('status') }}*</label>
                                <select class="form-control select-2-status" name="project_status_id">
                                    @foreach ($project_statuses as $status)
                                    <option value="{{$status->id}}"
                                        {{ (old('project_status_id', @$project->project_status_id) == $status->id ? 'selected' : '' )}}>
                                        {{$status->name}}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('project_status_id'))
                                <small class="text-danger">{{$errors->first('project_status_id')}}</small>
                                @endif
                            </div>
                        </fieldset>

                        <a href="{{route('projects.index')}}" class="btn btn-default">{{ __('return') }}</a>
                        <button type="submit" class="btn btn-primary"  @if( Route::currentRouteName() == 'projects.show' ) {{ 'disabled' }} @endif >{{ __('save') }}</button>
                        @admin()
                        @if(Route::currentRouteName() == 'projects.edit' && isset($project))
                            @if ($project->archived_at == null)
                                <a href="#" data-route="{{route('projects.archive', $project->id)}}"
                                data-title="{{ __('move_to_archive_?') }}" data-text="{{ __('are_you_sure_you_want_to_move_project_to_archive_?') }}" class="btn btn-warning float-right archiwe-warning">{{ __('move_to_archive') }}</a>
                            @else
                                <a href="#" data-route="{{route('projects.unarchive', $project->id)}}"
                                data-title="{{ __('move_to_current_?') }}" data-text="{{ __('are_ypu_sure_you_want_to_move_to_current_?') }}" class="btn btn-warning float-right archiwe-warning">{{ __('move_to_current') }}</a>
                            @endif
                        @endif
                        @endadmin
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

        $('#datePickerProject').datepicker({
            autoclose: true,
            format: "dd.mm.yyyy"
        });

        $('.select-2-status').select2();

        let date = $('#term').val();

        $(".contacts-select").select2({
            placeholder: "{{ __('select_person_or_people') }}",
            allowClear: true,
            templateResult: hideName,
            width: '100%',
        });

        $(".select2-clients").select2({ width: '100%'});

        $('.select2-clients').on('select2:select', function (e) {
            $(".contacts-select").val([]);
            $(".contacts-select").select2({
                placeholder: "{{ __('select_person_or_people') }}",
                allowClear: true,
                templateResult: hideName,
                width: '100%'
            });
        });

        function hideName(option) {
            let option_client = $(option.element).data('client');
            let client = $('.select2-clients').val();
            if (client == option_client) return option.text
            else return null;
        }

        $(".persons-select").select2({
            placeholder: "{{ __('select_person_or_people') }}",
            allowClear: true,
            width: '100%'
        });

        $('.archiwe-warning').click(function () {
            var route = $(this).data('route');
            var title = $(this).data('title');
            var text = $(this).data('text');
            swal({
                title: title,
                text: text,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{!! __('yes') !!}",
                cancelButtonText: "{!! __('no') !!}",
                closeOnConfirm: true,
                closeOnCancel: true
                },
            function () {
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: route,
                    success: function (response) {
                        window.location = response.route;
                    }
                });
            });
        });
    });

</script>
@endpush
