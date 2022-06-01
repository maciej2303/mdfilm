<div class="modal inmodal" id="modalWorkTimeEdit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">{{ __('close') }}</span></button>
                <h4 class="modal-title">{{ __('edit_work_time') }}</h4>
            </div>
            <form method="POST" action="" id="form_edit">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_id" value="{{$selectedUser->id}}">
                <input type="hidden" name="month" value="{{$month}}">
                <input type="hidden" name="year" value="{{$year}}">
                <input type="hidden" name="editWorkTime" value="1">
                <input type="hidden" name="work_time_id" id="work_time_id" value="{{old('work_time_id')}}">
                <input type="hidden" name="work_time" id="work_time" value="{{old('work_time')}}">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 m-b-sm {{$errors->has('date_edit') ? 'has-error' : ''}}">
                            <label>{{ __('day') }}*</label>
                            <div class="input-group date" id="datapicker_edit">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
                                    id="date_edit" type="text" name="date_edit" class="form-control" value="{{old('date_edit')}}" >
                            </div>
                            @if ($errors->has('date_edit'))
                                <small class="text-danger">{{$errors->first('date_edit')}}</small>
                            @endif
                        </div>

                        <div class="col-md-4 m-b-sm {{$errors->has('project_id_edit') ? 'has-error' : ''}}">
                            <label>{{ __('project') }}*</label>
                            <select class="select2-project-edit form-control" name="project_id_edit" id="project_edit">
                                @foreach ($projects as $project)
                                <option value="{{$project->id}}" {{$project->id == old('project_id_edit') ? 'selected' : ''}}>
                                    {{$project->name}}
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('project_id_edit'))
                                <small class="text-danger">{{$errors->first('project_id_edit')}}</small>
                            @endif
                        </div>

                        <div class="col-md-4 m-b-sm  {{$errors->has('work_time_type_id_edit') ? 'has-error' : ''}}">
                            <label>{{ __('work_time_type') }}*</label>
                            <select class="select2-workTimeType-edit form-control" name="work_time_type_id_edit">
                                @foreach ($workTimeTypes as $workTimeType)
                                <option value="{{$workTimeType->id}}" {{$workTimeType->id == old('work_time_type_id_edit') ? 'selected' : ''}}>
                                    {{$workTimeType->name}}
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('work_time_type_id_edit'))
                                <small class="text-danger">{{$errors->first('work_time_type_id_edit')}}</small>
                            @endif
                        </div>

                        <div class="col-md-4 m-b-sm {{$errors->has('date_edit') ? 'has-error' : ''}}">
                            <label>{{ __('houres') }}*</label>
                            <input type="number" class="form-control logged-hours" id="logged_hours_edit"
                                name="logged_hours_edit" step="0.01" min="0.01" max="24" value="{{old('logged_hours_edit')}}" >
                            @if ($errors->has('logged_hours_edit'))
                                <small class="text-danger">{{$errors->first('logged_hours_edit')}}</small>
                            @endif
                        </div>

                        <div class="col-md-8 m-b-sm {{$errors->has('task_edit') ? 'has-error' : ''}}">
                            <label>{{ __('task') }}*</label>
                            <input type="text" id="task_edit" class="form-control task"
                            name="task_edit" value="{{old('task_edit')}}">
                            @if ($errors->has('task_edit'))
                                <small class="text-danger">{{$errors->first('task_edit')}}</small>
                            @endif
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <a id="delete-button" type="button" class="delete-warning" data-id="" data-route=""
                        title="Usuń"><button type="button" class="btn btn-danger delete-warning">{{ __('delete') }}</button></a>
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="sumbit" class="btn btn-primary">{{ __('save') }}</button>
                </div>
            </form>

        </div>
    </div>
</div>

@if (count($errors) > 0 && old('editWorkTime') == 1)
    @push('errors')
    <script>
        $(document).ready(function(){
            $('#modalWorkTimeEdit').fadeIn('slow');
            $('#modalWorkTimeEdit').modal('show');
            let workTimeId =  $('#form_edit').find('#work_time_id').val();
            let route = @json(route('work_times.update', ":workTimeId"));
            let routeFinal = route.replace(':workTimeId', workTimeId);
            $('#form_edit').attr("action", routeFinal);

            let deleteRoute = @json(route('work_times.destroy'));
            $('#delete-button').data('id', workTimeId);
            $('#delete-button').data('route', deleteRoute);
        });
    </script>
    @endpush
@endif
