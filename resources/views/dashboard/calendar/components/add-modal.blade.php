<div class="modal inmodal" id="addEvent" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">{{ __('close') }}</span></button>
                <h4 class="modal-title">{{ __('add_event') }}</h4>
            </div>
            <form method="POST" action="{{route('events.store')}}">
                @csrf
                <input type="hidden" name="addEvent" value="1">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 m-b-sm {{$errors->has('event_type_id') && old('addEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('event_type') }}*</label>
                            <select class="select2-event-type form-control" name="event_type_id">
                                @foreach($eventTypes as $eventType)
                                <option value="{{$eventType->id}}" {{ old('event_type_id') == $eventType->id ? 'selected' : '' }}>{{$eventType->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('event_type_id') && old('addEvent') == 1)
                                <small class="text-danger">{{$errors->first('event_type_id')}}</small>
                            @endif
                        </div>

                        <div class="col-md-4 m-b-sm {{$errors->has('start_date') && old('addEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('start_day') }}*</label>
                            <div class="input-group date" id="addStartDatepicker">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="start_date" id="start_date"
                                    class="form-control" value="{{old('start_date')}}">
                            </div>
                            @if ($errors->has('start_date') && old('addEvent') == 1)
                                <small class="text-danger">{{$errors->first('start_date')}}</small>
                            @endif
                        </div>

                        <div class="col-md-4 m-b-sm {{$errors->has('end_date') && old('addEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('end_day') }}</label>
                            <div class="input-group date" id="addEndDatepicker">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="end_date" id="end_date"
                                    class="form-control" value="{{old('end_date')}}">
                            </div>
                            @if ($errors->has('end_date') && old('addEvent') == 1)
                                <small class="text-danger">{{$errors->first('end_date')}}</small>
                            @endif
                        </div>

                        <div class="col-md-4 m-b-sm"></div>


                        <div class="col-md-4 m-b-sm {{$errors->has('start_time') && old('addEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('start_hour') }}</label>
                            <div class="input-group date" id="startTimePicker" data-target-input="nearest">
                                <div class="input-group-append" data-target="#startTimePicker"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-addon"><i class="fa fa-clock"></i></div>
                                </div>
                                <input type="text" class="form-control datetimepicker-input" name="start_time" id="start_time"
                                    data-target="#startTimePicker" value="{{old('start_time')}}" />
                            </div>
                            @if ($errors->has('start_time') && old('addEvent') == 1)
                                <small class="text-danger">{{$errors->first('start_time')}}</small>
                            @endif
                        </div>

                        <div class="col-md-4 m-b-sm {{$errors->has('start_date') && old('end_time') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('end_hour') }}</label>
                            <div class="input-group date" id="endTimePicker" data-target-input="nearest">
                                <div class="input-group-append" data-target="#endTimePicker"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-addon"><i class="fa fa-clock"></i></div>
                                </div>
                                <input type="text" class="form-control " name="end_time" id="end_time" value={{old('end_time')}}
                                    data-target="#endTimePicker" />
                            </div>
                            @if ($errors->has('end_time') && old('addEvent') == 1)
                                <small class="text-danger">{{$errors->first('end_time')}}</small>
                            @endif
                        </div>

                        <div class="col-md-12 m-b-sm {{$errors->has('event') && old('addEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('event') }}*</label>
                            <input type="text" class="form-control" value="{{old('event')}}" name="event">
                            @if ($errors->has('event') && old('addEvent') == 1)
                                <small class="text-danger">{{$errors->first('event')}}</small>
                            @endif
                        </div>

                        <div class="col-md-12 m-b-sm {{$errors->has('description') && old('addEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{__('description') }}</label>
                            <textarea class="form-control" style="height:100px" name="description">{{old('description')}}</textarea>
                            @if ($errors->has('description') && old('addEvent') == 1)
                                <small class="text-danger">{{$errors->first('description')}}</small>
                            @endif
                        </div>

                        <div class="col-md-4 m-b-sm {{$errors->has('project_id') && old('addEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('project') }}</label>
                            <select class="select2-projects form-control project-select" name="project_id"
                            onchange="getMembers(this.value)">
                                <option value="">---</option>
                                @foreach ($projects as $project)
                                <option value="{{$project->id}}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{$project->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('project_id') && old('addEvent') == 1)
                                <small class="text-danger">{{$errors->first('project_id')}}</small>
                            @endif
                        </div>

                        <div class="col-8 m-b-sm {{$errors->has('members') && old('addEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('team') }}</label>
                            <select class="select2-team form-control user-select" name="members[]" multiple="multiple">
                                <option value="">---</option>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}"
                                        @if(old('members'))
                                        {{ in_array($user->id, old('members')) ? 'selected' : '' }}
                                        @endif>{{$user->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('members') && old('addEvent') == 1)
                                <small class="text-danger">{{$errors->first('members')}}</small>
                            @endif
                        </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
    function getMembers(project_id) {
        $.ajax({
            url: @json(route('calendar.getProjectsMembers')),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "post",
            data: {project_id: project_id} ,
            success: function (response) {
                $('.user-select').empty();
                response.members.forEach(element => {
                    $('.user-select').append(new Option(element.name, element.id))
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
            }
        });
    }
</script>
@endpush

@if (count($errors) > 0 && old('addEvent') == 1)
@push('errors')
<script>
    $(document).ready(function () {
        $('#addEvent').fadeIn('slow');
        $('#addEvent').modal('show');
    });

</script>
@endpush
@endif
