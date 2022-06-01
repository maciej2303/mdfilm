<div class="modal inmodal" id="modalWorkTime" style="overflow: hidden" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">{{ __('close') }}</span></button>
                <h4 class="modal-title">{{ __('add_work_time') }}</h4>
            </div>
            <form method="POST" action="{{route('work_times.store')}}">
                @csrf
                <input type="hidden" name="createWorkTime" value="1">
                <input type="hidden" name="user_id" value="{{$selectedUser->id ?? null}}">
                <input type="hidden" name="month" value="{{$month ?? null}}">
                <input type="hidden" name="year" value="{{$year ?? null}}">
                <div class="modal-body">
                    <div class="inputs">
                        @if(!old('createWorkTime'))
                            <div class="row module" id="1">
                                <div class="col-md-4 m-b-sm">
                                    <label>{{ __('day') }}*</label>
                                    <div class="input-group date addWorkTimeDatepicker">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
                                            type="text" name="date[]" class="form-control" value="" >
                                    </div>
                                </div>

                                <div class="col-md-4 m-b-sm">
                                    <label>{{ __('project') }}*</label>
                                    <select class="select2-project form-control" name="project[]">
                                        @foreach ($projects as $project)
                                        <option value="{{$project->id}}">
                                            {{$project->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 m-b-sm">
                                    <label>{{ __('work_time_type') }}*</label>
                                    <select class="select2-workTimeType form-control" name="workTimeType[]">
                                        @foreach ($workTimeTypes as $workTimeType)
                                        <option value="{{$workTimeType->id}}">
                                            {{$workTimeType->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 m-b-sm">
                                    <label>{{ __('houres') }}*</label>
                                    <input type="number" class="form-control logged-hours"
                                        name="logged_hours[]" step="0.01" min="0.01" max="24" >
                                </div>

                                <div class="col-md-8 m-b-sm">
                                    <label>{{ __('task') }}*</label>
                                    <input type="text" class="form-control task" name="task[]" >
                                </div>

                            </div>
                        @else
                            @for($i=0; $i < count(old('date')); $i++)
                            <div class="row module" id="{{$i}}">
                                <div class="col-md-4 m-b-sm {{$errors->has('date.'.$i) && old('createWorkTime') == 1 ? 'has-error' : ''}}">
                                    <label>{{ __('day') }}*</label>
                                    <div class="input-group date addWorkTimeDatepicker">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="{{'date'.$i}}"
                                            type="text" name="date[]" class="form-control" value="{{old('date.'.$i)}}" >
                                    </div>
                                    @if ($errors->has('date.'.$i) && old('createWorkTime') == 1)
                                    <small class="text-danger">{{$errors->first('date.'.$i)}}</small>
                                    @endif
                                </div>

                                <div class="col-md-4 m-b-sm  {{$errors->has('project.'.$i) && old('createWorkTime') == 1 ? 'has-error' : ''}} {{$errors->has('project') ? 'has-error' : ''}}" >
                                    <label>{{ __('project')}}*</label>
                                    <select class="select2-project form-control"
                                        name="project[]">
                                        @foreach ($projects as $project)
                                        <option value="{{$project->id}}"
                                            {{ old('project.'.$i) == $project->id ? 'selected' : '' }}>
                                            {{$project->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('project.'.$i) && old('createWorkTime') == 1)
                                    <small class="text-danger">{{$errors->first('project.'.$i)}}</small>
                                    @endif
                                    @if ($errors->has('project') && old('createWorkTime') == 1)
                                    <small class="text-danger">{{$errors->first('project')}}</small>
                                    @endif
                                </div>

                                <div class="col-md-4 m-b-sm {{$errors->has('workTimeType.'.$i) && old('createWorkTime') == 1 ? 'has-error' : ''}}">
                                    <label>{{ __('work_time_type') }}*</label>
                                    <select class="select2-workTimeType form-control"
                                        name="workTimeType[]">
                                        @foreach ($workTimeTypes as $workTimeType)
                                        <option value="{{$workTimeType->id}}"
                                            {{ old('workTimeType.'.$i) == $workTimeType->id ? 'selected' : '' }}>
                                            {{$workTimeType->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('workTimeType.'.$i) && old('createWorkTime') == 1)
                                    <small class="text-danger">{{$errors->first('workTimeType.'.$i)}}</small>
                                    @endif
                                    @if ($errors->has('workTimeType') && old('createWorkTime') == 1)
                                    <small class="text-danger">{{$errors->first('workTimeType')}}</small>
                                    @endif
                                </div>

                                <div class="col-md-4 m-b-sm {{$errors->has('logged_hours.'.$i) && old('createWorkTime') == 1 ? 'has-error' : ''}}">
                                    <label>{{ __('houres') }}*</label>
                                    <input type="number"
                                        class="form-control logged-hours"
                                        name="logged_hours[]" value="{{old('logged_hours.'.$i)}}" step="0.01" min="0.01" max="24" >
                                    @if ($errors->has('logged_hours.'.$i) && old('createWorkTime') == 1)
                                    <small class="text-danger">{{$errors->first('logged_hours.'.$i)}}</small>
                                    @endif
                                </div>

                                <div class="col-md-8 m-b-sm {{$errors->has('task.'.$i) && old('createWorkTime') == 1 ? 'has-error' : ''}}">
                                    <label>{{ __('task') }}*</label>
                                    <input type="text" class="form-control task"
                                        name="task[]" value="{{old('task.'.$i)}}">
                                    @if ($errors->has('task.'.$i) && old('createWorkTime') == 1)
                                    <small class="text-danger">{{$errors->first('task.'.$i)}}</small>
                                    @endif
                                </div>

                            </div>
                            @endfor
                        @endif
                    </div>
                    <hr>
                    <button type="button" class="btn btn-primary" id="new-work-time">{{ __('add_another') }}</button>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('save') }}</button>
                </div>
        </div>
        </form>
    </div>
</div>
@if (count($errors) > 0 && old('createWorkTime') == 1)
    @push('errors')
    <script>
        $(document).ready(function(){
            $('#modalWorkTime').fadeIn('slow');
            $('#modalWorkTime').modal('show');
        });
    </script>
    @endpush
@endif
