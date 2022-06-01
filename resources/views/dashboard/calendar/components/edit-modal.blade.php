<div class="modal inmodal" id="editEvent" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">{{__('close') }}</span></button>
                <h4 class="modal-title">{{ __('edit_event') }}</h4>
            </div>
            <form method="POST" action="" id="form_event_edit">
                @csrf
                <input type="hidden" name="editEvent" value="1">
                <input type="hidden" name="event" id="event" value="{{old('event')}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 m-b-sm {{$errors->has('event_type_id_edit') && old('editEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('event_type') }}*</label>
                            <select class="select2-event-type form-control" name="event_type_id_edit" id="event_type_id_edit">
                                @foreach($eventTypes as $eventType)
                                <option value="{{$eventType->id}}" {{ old('event_type_id_edit') == $eventType->id ? 'selected' : '' }}>{{$eventType->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('event_type_id_edit') && old('editEvent') == 1)
                                <small class="text-danger">{{$errors->first('event_type_id_edit')}}</small>
                            @endif
                        </div>

                        <div class="col-md-4 m-b-sm {{$errors->has('start_date_edit') && old('editEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('start_day') }}*</label>
                            <div class="input-group date" id="addStartDatepickerEdit">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="start_date_edit"
                                    id="start_date_edit" class="form-control" value="{{old('start_date_edit')}}">
                            </div>
                            @if ($errors->has('start_date_edit') && old('editEvent') == 1)
                                <small class="text-danger">{{$errors->first('start_date_edit')}}</small>
                            @endif
                        </div>

                        <div class="col-md-4 m-b-sm {{$errors->has('end_date_edit') && old('editEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('end_day') }}</label>
                            <div class="input-group date" id="addEndDatepickerEdit">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="end_date_edit"
                                    id="end_date_edit" class="form-control" value="{{old('end_date_edit')}}">
                            </div>
                            @if ($errors->has('end_date_edit') && old('editEvent') == 1)
                                <small class="text-danger">{{$errors->first('end_date_edit')}}</small>
                            @endif
                        </div>

                        <div class="col-md-4 m-b-sm"></div>


                        <div class="col-md-4 m-b-sm {{$errors->has('start_time_edit') && old('editEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('start_hour') }}</label>
                            <div class="input-group date" id="startTimePickerEdit" data-target-input="nearest">
                                <div class="input-group-append" data-target="#startTimePickerEdit"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-addon"><i class="fa fa-clock"></i></div>
                                </div>
                                <input type="text" class="form-control datetimepicker-input" name="start_time_edit" id="start_time_edit"
                                    data-target="#startTimePickerEdit" value="{{old('start_time_edit')}}" />
                            </div>
                            @if ($errors->has('start_time_edit') && old('editEvent') == 1)
                                <small class="text-danger">{{$errors->first('start_time_edit')}}</small>
                            @endif
                        </div>

                        <div class="col-md-4 m-b-sm {{$errors->has('end_time_edit') && old('end_time_edit') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('end_hour') }}</label>
                            <div class="input-group date" id="endTimePickerEdit" data-target-input="nearest">
                                <div class="input-group-append" data-target="#endTimePickerEdit"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-addon"><i class="fa fa-clock"></i></div>
                                </div>
                                <input type="text" class="form-control datetimepicker-input" name="end_time_edit" id="end_time_edit"
                                    data-target="#endTimePickerEdit" value="{{old('end_time_edit')}}" />
                            </div>
                            @if ($errors->has('end_time_edit') && old('editEvent') == 1)
                                <small class="text-danger">{{$errors->first('end_time_edit')}}</small>
                            @endif
                        </div>

                        <div class="col-md-12 m-b-sm {{$errors->has('event_edit') && old('editEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('event') }}*</label>
                            <input type="text" class="form-control" value="{{old('event_edit')}}" name="event_edit" id="event_edit">
                            @if ($errors->has('event_edit') && old('editEvent') == 1)
                                <small class="text-danger">{{$errors->first('event_edit')}}</small>
                            @endif
                        </div>

                        <div class="col-md-12 m-b-sm {{$errors->has('description_edit') && old('editEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('description') }}</label>
                            <textarea class="form-control" style="height:100px" name="description_edit" id="description_edit">{{old('description_edit')}}</textarea>
                            @if ($errors->has('description_edit') && old('editEvent') == 1)
                                <small class="text-danger">{{$errors->first('description_edit')}}</small>
                            @endif
                        </div>

                        <div class="col-md-4 m-b-sm {{$errors->has('project_id_edit') && old('editEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('project') }}</label>
                            <select class="select2-projects form-control project-select" name="project_id_edit" id="project_id_edit"
                            onchange="getMembers(this.value)">
                                <option value="">---</option>
                                @foreach ($projects as $project)
                                <option value="{{$project->id}}" {{ old('project_id_edit') == $project->id ? 'selected' : '' }}>{{$project->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('project_id_edit') && old('editEvent') == 1)
                                <small class="text-danger">{{$errors->first('project_id_edit')}}</small>
                            @endif
                        </div>

                        <div class="col-8 m-b-sm {{$errors->has('members_edit') && old('editEvent') == 1 ? 'has-error' : ''}}">
                            <label>{{ __('team') }}</label>
                            <select class="select2-team form-control user-select" name="members_edit[]" id="members_edit" multiple="multiple">
                                <option value="">---</option>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}"
                                        @if(old('members_edit'))
                                        {{ in_array($user->id, old('members_edit')) ? 'selected' : '' }}
                                        @endif>{{$user->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('members_edit') && old('editEvent') == 1)
                                <small class="text-danger">{{$errors->first('members_edit')}}</small>
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

@if (count($errors) > 0 && old('editEvent') == 1)
    @push('errors')
    <script>
        $(document).ready(function(){
            let event =  JSON.parse($('#form_event_edit').find('#event').val());
            let route = @json(route('events.update', ":eventId"));
            let routeFinal = route.replace(':eventId', event.id);
            $('#form_event_edit').attr("action", routeFinal);
            $('#editEvent').fadeIn('slow');
            $('#editEvent').modal('show');
        });
    </script>
    @endpush
@endif
