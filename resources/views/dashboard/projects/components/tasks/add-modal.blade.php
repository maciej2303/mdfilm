<div class="modal inmodal" id="addTodo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">{{ __('close') }}</span></button>
                <h4 class="modal-title">{{ __('add_to_do_position') }}</h4>
            </div>
            <form id="add-task-form" method="POST" action="{{route('tasks.store')}}">
                @csrf
                <div class="overflow-visible modal-body">
                    <div class="row">
                        <div class="col-md-12 {{$errors->has('content') && old('createTask') == 1  ? 'has-error' : ''}}">
                            <input type="hidden" name="relationable_id" value={{isset($projectElementComponentVersion) ? $projectElementComponentVersion->id : $project->id}}>
                            <input type="hidden" name="relationable_type" value={{get_class(isset($projectElementComponentVersion) ? $projectElementComponentVersion : $project)}}>
                            <input type="hidden" name="createTask" value="1">
                            <label>{{ __('content') }}*</label><br>
                            <to-do-modal-input :users="{{$users}}" :project="{{$project}}"></to-do-modal-input>
                            @if ($errors->has('content') && old('createTask') == 1)
                            <small class="text-danger">{{$errors->first('content')}}</small>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="submit" id="btn-save" class="btn btn-primary">{{ __('save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@if (count($errors) && old('createTask') == 1))
    @push('errors')
    <script>
        $(document).ready(function(){
            $('#addTodo').fadeIn('slow');
            $('#addTodo').modal('show');
            $('#addTodo').find('.editor').html(@json(old('content')));
        });
    </script>
    @endpush
@endif
