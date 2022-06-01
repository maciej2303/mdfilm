@extends('layouts.app')
@section('title', 'Użytkownicy')
@push('css')
@endpush
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">

            <div class="ibox ">
                <div class="ibox-title">
                    <h5>{{ __('project_statuses') }}</h5>
                    <div class="ibox-tools">

                    </div>
                </div>

                <div class="ibox-content">

                    <div class="row m-b">
                        <div class="col-md-10">
                            <a href="{{route('project_statuses.create')}}" class="btn btn-primary"><i
                                    class="fas fa-plus"></i> {{ __('add_project_status') }}</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <div class="dd" id="nestable2">
                                <ol class="dd-list">
                                    @foreach ($projectStatuses as $projectStatus)
                                        <li class="dd-item" data-id="{{$projectStatus->id}}">
                                            <div class="dd-handle">
                                                <i class="fas fa-square" style="color:{{$projectStatus->colour}}"></i>
                                                {{ @$translations[$projectStatus->id]['name'][$lang] }}
                                                <span class="float-right dd-nodrag">
                                                    <a type="button"
                                                        class="btn btn-primary btn-xs tooltip-more position-relative"
                                                        href="{{route('project_statuses.edit', $projectStatus->id)}}" title="{{ __('edit') }}"
                                                        ><i class="fas fa-pen"></i>
                                                    </a>
                                                    <a type="button"
                                                        class="btn btn-danger btn-xs tooltip-more delete-warning" data-id="{{$projectStatus->id}}"  data-route="{{route('project_statuses.destroy')}}"
                                                        data-text="{{ __('deleting_this_element_will_cause_deleteing_every_connected_projects') }}"
                                                        title="Usuń"><i class="fas fa-trash"></i></a> </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@push('js')
<!-- Nestable List -->
<script src="{{asset('js/plugins/nestable/jquery.nestable.js')}}"></script>
<script src="{{asset('js/delete-modal.js')}}"></script>

<script>
    $(document).ready(function () {

        var updateOutput = function (e) {
                var list = e.length ? e : $(e.target),
                    output = list.data('output');
                if (window.JSON) {
                    output.val(window.JSON.stringify(list.nestable('serialize')));
                } else {
                    output.val('JSON browser support required for this demo.');
                }
            };

            $('#nestable2').nestable({
                group: 1,
                maxDepth: 1,
                noDragClass:'dd-nodrag'
            }).on('change', updateOutput);



            updateOutput($('#nestable2').data('output', $('#nestable2-output')));

            $('.dd').on('change', function() {
                let order = $('.dd').nestable('serialize');
                $.ajax({
                    url: @json(route('project_statuses.change_order')),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "post",
                    data: {order: order} ,
                    success: function (response) {
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                    }
                });
            });
    });

</script>

<script>

</script>
@endpush
@endsection
