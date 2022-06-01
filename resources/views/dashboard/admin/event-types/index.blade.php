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
                    <h5>{{ __('type_of_event') }}</h5>
                    <div class="ibox-tools">

                    </div>
                </div>

                <div class="ibox-content">

                    <div class="row m-b">
                        <div class="col-md-10">
                            <a href="{{route('event_types.create')}}" class="btn btn-primary"><i
                                    class="fas fa-plus"></i> {{ __('add_event_type') }}</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <div class="dd" id="nestable2">
                                <ol class="dd-list">
                                    @foreach ($eventTypes as $eventType)
                                        <li class="dd-item" data-id="{{$eventType->id}}">
                                            <div class="dd-handle">
                                                <i class="fas fa-square" style="color:{{$eventType->colour}}"></i>
                                                {{ @$translations[$eventType->id]['name'][$lang] }}
                                                <span class="float-right dd-nodrag">
                                                    <a type="button"
                                                        class="btn btn-primary btn-xs tooltip-more position-relative"
                                                        href="{{route('event_types.edit', $eventType->id)}}" title="{{ __('edit') }}"
                                                        ><i class="fas fa-pen"></i>
                                                    </a>
                                                    @if($eventType->removable == 1)
                                                    <a type="button"
                                                        class="btn btn-danger btn-xs tooltip-more delete-warning" data-id="{{$eventType->id}}"  data-route="{{route('event_types.destroy')}}"
                                                        data-text="{{ __('deleting_this_element_will_cause_deleteing_every_connected_projects') }}"
                                                        title="{{ __('delete') }}"><i class="fas fa-trash"></i></a> </span>
                                                    @endif
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
                url: @json(route('event_types.change_order')),
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
@endpush
@endsection
