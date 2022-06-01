<div class="modal inmodal" id="editTodo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">{{ __('close') }}</span></button>
                <h4 class="modal-title">{{ __('edit_to_do_positon') }}</h4>
            </div>
            <form method="POST" action="" id="form_edit">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="editTask" value="1">
                        <input type="hidden" id="task_id" name="taskId" value="{{old('taskId')}}">
                        <input type="hidden" name="task" id="task" value="{{old('task')}}">
                        <div class="col-md-12 {{$errors->has('content') && old('editTask') == 1  ? 'has-error' : ''}}">
                            <label>{{ __('content') }}*</label><br>
                            <to-do-modal-input :users="{{$users}}" :project="{{$project}}"></to-do-modal-input>
                            @if ($errors->has('content') && old('editTask') == 1)
                                <small class="text-danger">{{$errors->first('content')}}</small>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <h5>{{ __('change_history') }}</h5>
                    <div class="history-box" id="history-box">
                        @include('dashboard.projects.components.tasks.history-box', ['logs' => old('taskId') != null ? \App\Models\Task::find(old('taskId'))->changeLogs()->orderBy('created_at', 'desc')->get() : collect()])
                    </div>
                </div>
                <div class="modal-footer">
                    @admin
                    <a id="delete-button" type="button" class="delete-warning" data-id="" data-route="" title="Usuń">
                        <button type="button" class="btn btn-danger delete-warning">{{ __('delete') }}</button>
                    </a>
                    @endadmin
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('save') }}</button>
                </div>
            </form>
    </div>
</div>

        </div>
@if (count($errors) && old('editTask') == 1)
    @push('errors')
    <script>
        $(document).ready(function(){
            $('#editTodo').fadeIn('slow');
            $('#editTodo').modal('show');
            let taskId =  $('#form_edit').find('#task_id').val();
            let route = @json(route('tasks.update', ":taskId"));
            let routeFinal = route.replace(':taskId', taskId);
            $('#form_edit').attr("action", routeFinal);
            $('#editTodo').find('#content-field').html(@json(old('content', '')));
            let deleteRoute = @json(route('tasks.destroy'));
            $('#delete-button').data('id', taskId);
            $('#delete-button').data('route', deleteRoute);
        });
    </script>
    @endpush
@endif
