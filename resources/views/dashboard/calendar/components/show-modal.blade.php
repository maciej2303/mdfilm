<!-- MODAL POKAŻ WYDARZENIE -->
<div class="modal inmodal" id="showEvent" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">{{ __('close')}}</span></button>
                <h4 class="modal-title">{{ __('show_event') }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 m-b-sm">
                        <label>{{ __('event_type') }}*</label>
                        <select class="select2-show-event-type form-control" name="show_event_type_id" id="showEventType" disabled>
                            @foreach($eventTypes as $eventType)
                                <option value="{{$eventType->id}}">{{$eventType->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 m-b-sm">
                        <label>{{ __('start_day') }}*</label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text"
                                class="form-control" id="show_start_date" value="" disabled>
                        </div>
                    </div>

                    <div class="col-md-4 m-b-sm">
                        <label>{{ __('end_day') }}</label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text"
                                class="form-control" id="show_end_date" value="" disabled>
                        </div>
                    </div>

                    <div class="col-md-4 m-b-sm"></div>

                    <div class="col-md-4 m-b-sm">
                        <label> {{ __('start_hour') }}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-clock"></i></span><input type="text"
                                class="form-control" id="show_start_time" disabled>
                        </div>
                    </div>

                    <div class="col-md-4 m-b-sm">
                        <label>{{ __('end_hour') }}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-clock"></i></span><input type="text"
                                class="form-control" id="show_end_time" value="" disabled>
                        </div>
                    </div>

                    <div class="col-md-12 m-b-sm">
                        <label>{{ __('event') }}*</label>
                        <input type="text" class="form-control" id="show_event" value="" disabled>
                    </div>

                    <div class="col-md-12 m-b-sm">
                        <label>{{ __('description') }}</label>
                        <textarea class="form-control" style="height:100px" id="show_description" disabled></textarea>
                    </div>

                    <div class="col-md-4 m-b-sm">
                        <label>{{ __('project') }}</label>
                        <select class="select2_demo_1 form-control" id="show_project_id" disabled>
                            @foreach ($projects as $project)
                            <option value="{{$project->id}}">{{$project->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-8 m-b-sm">
                        <label>{{ __('team') }}</label>
                        <select class="select2-show-team form-control user-select" id="show_members" multiple="multiple">
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @admin
                <a type="button" class="btn btn-danger tooltip-more delete-warning"
                    data-id="" data-route="{{route('events.destroy')}}"
                    title="Usuń">{{ __('delete') }}</a>
                @endadmin
                <button type="button" class="btn btn-primary edit-modal-button" data-dismiss="modal" data-toggle="modal" data-route="{{route('events.update', ":id")}}" data-target="#editEvent">{{ __('edit') }}</button>
                <button type="button" class="btn btn-white" data-dismiss="modal">{{ __('cancel') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- /MODAL POKAŻ WYDARZENIE -->
@push('js')
<script>
    $(".select2-show-team").select2({
        width: '100%',
    });
</script>
@endpush
