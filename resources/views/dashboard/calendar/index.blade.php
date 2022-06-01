@extends('layouts.app')
@section('title', 'Użytkownicy')
@push('calendar')
<link href="{{asset('css/plugins/fullcalendar/fullcalendar.css')}}" rel="stylesheet">
<link href="{{asset('css/plugins/fullcalendar/fullcalendar.print.css')}}" rel='stylesheet' media='print'>
<link href="{{asset('css/plugins/tempusdominus/tempusdominus-bootstrap-4.min.css')}}" rel="stylesheet">
@endpush

@section('content')
<div class="wrapper wrapper-content animated fadeInRight pb-0">
    <div class="ibox-title">
        <h5>{{ __('calendar') }}</h5>
        <div class="ibox-tools">

        </div>
    </div>
    <div class="ibox-content">
        <input type="hidden" value="{{$date}}" id="date">
        <div class="row m-b">
            <div class="col-md-2">
                @foreach ($eventTypes as $eventType)
                <a data-toggle="modal" data-target="#addEvent" data-event-type="{{$eventType->id}}"
                    data-reset="true"><span class="eventList" style="background-color:{{$eventType->colour}};"><i
                            class="fas fa-plus"></i> {{$eventType->name}}</span></a>
                @endforeach
                <h4 style="display:block; margin-top:25px;">{{ __('filters') }}</h4>

                <label class="">{{__('event_type') }}</label>
                <select class="filter form-control" id="eventTypeFilter" multiple="multiple" onchange="filter()">
                    @foreach ($eventTypes as $eventType)
                    <option @if(isset($session_filters['eventTypeFilter']) && in_array($eventType->id, $session_filters['eventTypeFilter'])) selected @endif value="{{$eventType->id}}">{{$eventType->name}}</option>
                    @endforeach
                </select>

                <label class="m-t-xs">{{ __('project') }}</label>
                <select class="filter form-control" id="projectFilter" multiple="multiple" onchange="filter()">
                    @foreach ($projects as $project)
                        <option @if(isset($session_filters['projectFilter']) && in_array($project->id, $session_filters['projectFilter'])) selected @endif value="{{$project->id}}">{{$project->name}}</option>
                    @endforeach
                </select>

                <label class="m-t-xs">{{ __('team') }}</label>
                <select class="filter form-control" id="userFilter" multiple="multiple" onchange="filter()">
                    @foreach ($users as $user)
                        <option @if(isset($session_filters['eventTypeFilter']) && in_array($user->id, $session_filters['eventTypeFilter'])) selected @endif value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                </select>

                <button class="btn btn-sm btn-primary m-t-sm" onclick="resetFilters()">{{ __('reset_filters') }}</button>
            </div>
            <div class="col-md-10">
                <div class="container-calendar" id="calendar"></div>
            </div>
        </div>
    </div>
    @include('dashboard.calendar.components.add-modal')
    @include('dashboard.calendar.components.edit-modal')
    @include('dashboard.calendar.components.show-modal')
</div>

@push('js')
<script src="{{asset('js/plugins/fullcalendar/moment.min.js')}}"></script>
<script src="{{asset('js/plugins/fullcalendar/fullcalendar.min.js')}}"></script>
<script src="{{asset('js/plugins/fullcalendar/lang-all.min.js')}}"></script>
<script src="{{asset('js/plugins/tempusdominus/tempusdominus-bootstrap-4.min.js')}}"></script>
<script src="{{asset('js/calendar/add-modal.js')}}"></script>
<script src="{{asset('js/delete-modal.js')}}"></script>
<script src="{{asset('js/calendar/show-modal.js')}}"></script>
<script src="{{asset('js/calendar/edit-modal.js')}}"></script>
<script>

    function filter()
    {
        $('#calendar').fullCalendar( 'refetchEvents' )
        $('.avatar-in-board').tooltip();
    }

    function resetFilters()
    {
        $(".filter").val('').trigger('change')
    }

    $(document).ready(function () {

		$(".filter").select2({
            placeholder: "{{ __('filter') }}",
            allowClear: true
        });

        var date = new Date();
        if ($('#date').val() != null) {
            date = $('#date').val();
        }
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next,today',
                center: 'title',
                right: null,
            },
            // editable: true,
            droppable: true, // this allows things to be dropped onto the calendar

            lang: '{{ \App::getLocale() }}',
            drop: function () {
                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }
            },
            events: {
                url: 'calendar',
                type: 'GET',
                data: function() { // a function that returns an object
                    return {
                        eventTypeFilter: $("#eventTypeFilter").val(),
                        projectFilter: $("#projectFilter").val(),
                        userFilter: $("#userFilter").val(),
                    };
                },
            },
            eventClick: function (event) {
                openShowModal(event);
            },
            eventRender: function (event, element, view) {
                let hours = '<br /> <span class="fc-hours">' + event.hours + '</span>';
                element.find('.fc-title').append(hours);
                let box = '<div class="agile-detail overflow-hidden"><div class="d-block" id="' +
                    event.id + '"></div></div>';
                element.find('.fc-title').append(box);
                let members = event.members;
                members.forEach(function (member, index) {
                    let background = 'gray';
                    let image = '';
                    if (member.avatar != null) {
                        image = 'd-none';
                        background = 'transparent url(&quot;' + member.avatar +
                            '&quot;) no-repeat scroll 0% 0% / 30px 30px content-box border-box';
                    }
                    let avatar = `<div aria-hidden="true" data-toggle="tooltip"
                    class="vue-avatar--wrapper avatar-in-board float-left rounded-circle tooltip-more mr-1 mt-1"
                    style="display: flex; width: 30px; height: 30px; border-radius: 50%; font: 12px / 30px Helvetica, Arial, sans-serif; align-items: center;
                    justify-content: center; text-align: center; user-select: none;
                    background: ` + background + `;
                    color: white;"
                    title="` + member.name + `" data-placement="bottom">
                    <span class="` + image + `">` + member.name.substring(0, 2).toUpperCase() + `</span></div>`
                    element.find('#' + event.id).append(avatar);
                });
                $('.fc-day-number').prop('title', 'Dodaj wydarzenie')
                $('.fc-day-number').tooltip();
                $('body').tooltip({ selector: '[data-toggle="tooltip"]' });
            },

            timeFormat: 'H:mm',

            dayClick: function (date, jsEvent, view) {
                let day = new Date(date);
                $("#addStartDatepicker").datepicker("setDate", day);
                $('#show_start_time').val(date.start_time);
                $('#addEvent').fadeIn('slow');
                $('#addEvent').modal('show');
            },
        });
        $('.fc-day-number').prop('title', 'Dodaj wydarzenie')
        $('.fc-day-number').tooltip()
        $('#calendar').fullCalendar('gotoDate', date);
    });
</script>
@endpush
@endsection