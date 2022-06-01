@extends('layouts.app')
@section('title', 'Użytkownicy')
@push('css')
<link href="{{asset('css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
<link href="{{asset('css/plugins/datapicker/datepicker3.css')}}" rel="stylesheet">

@endpush

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-10">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalWorkTime"><i class="fas fa-clock"></i> {{ __('add_work_time') }}</button>
                        </div>
                        @admin
                        <div class="col-md-2">
                            <select class="user-select form-control" name="chosen_user" id="chosen_user">
                                @foreach ($users as $user)
                                <option value="{{$user->id}}" {{$user->id == $selectedUser->id ? 'selected' : ''}}>{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endadmin
                    </div>
                </div>
            </div>

            <div class="ibox">
                <div class="ibox-title">
                    <h5>{{ __('work_time') }}</h5>
                    <div class="ibox-tools"></div>
                </div>
                <div class="ibox-content">
                    <div class="row m-b">
                        <div class="col-2">
                            <a href="{{route('work_times.index').'?selectedUser='.$selectedUser->id.'&month='.($month-1).'&year='.$year}}">
                                <button class="btn btn-primary btn-xs"><i class="fas fa-arrow-left"></i> {{ __('month_before') }}</button>
                            </a>
                        </div>
                        <div class="col-8 text-center">
                            <h3>{{$monthName}} {{$year}}</h3>
                        </div>
                        <div class="col-2 text-right">
                            <a href="{{route('work_times.index').'?selectedUser='.$selectedUser->id.'&month='.($month+1).'&year='.$year}}">
                                <button class="btn btn-primary btn-xs">{{ __('next_month') }} <i
                                        class="fas fa-arrow-right"></i></button>
                            </a>
                        </div>
                    </div>
                    <div class="project-table-wrapper">
                        <table class="table table-bordered table-project">
                            <thead>
                                <tr>
                                    <th class="table-project-day">{{ __('day_of_the_month') }}</th>
                                    @foreach($userProjectsWithLoggedHours as $project)
                                        <th colspan="2" class="table-project">{{$project->name}}</th>
                                    @endforeach
                                    <th colspan="1" class="table-project-hours-total text-center">{{ __('houres') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($days as $day=>$data)
                                    <tr>
                                        <td>{{$day}}</td>
                                            @foreach ($data->projects as $projectId => $projectData)
                                                <td class="table-project-hours">{{$projectData['dailyHours']}}</td>
                                                <td class="table-project-tasks">
                                                    @foreach ($projectData['dayWorkTimes'] as $dayWorkTime)
                                                    <a data-toggle="modal"
                                                    data-target="#modalWorkTimeEdit" data-worktime="{{$dayWorkTime}}" data-route="{{route('work_times.update', $dayWorkTime->id)}}"
                                                    data-delete={{route('work_times.destroy')}}>
                                                        <span class="label" style="background-color: {{$dayWorkTime->workTimeType->colour}}; color: white;">
                                                            {{$dayWorkTime->task}} ({{(float)$dayWorkTime->logged_hours}})
                                                        </span>
                                                    </a>
                                                    @endforeach
                                                </td>
                                            @endforeach
                                        <td class="text-center">{{$data->dayHours}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <thead>
                                <tr>
                                    <th class="table-project-day">{{ __('summary') }}</th>
                                    @foreach($userProjectsWithLoggedHours as $project)
                                        <th class="text-center">{{$project->projectHours}}</th>
                                        <th class="table-project-total">
                                            @foreach ($project->workTimeTypes as $workTimeType => $data)
                                            <span class="label" style="background-color: {{$data['colour']}}; color: white;">{{$workTimeType}} ({{(float)$data['hours']}})
                                            </span>
                                            @endforeach
                                    @endforeach
                                    <th class="text-center">
                                        <h3>{{$allHours}}</h3>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('dashboard.work-times.components.add-modal')
<!-- MODAL EDYTUJ CZAS PRACY -->
@include('dashboard.work-times.components.edit-modal')
<!-- /MODAL EDYTUJ CZAS PRACY -->

<!-- /MODALS -->
@push('js')

<script src="{{asset('js/delete-modal.js')}}"></script>
<script src="{{asset('js/work-time/add-modal.js')}}"></script>
<script src="{{asset('js/work-time/edit-modal.js')}}"></script>
<script>
    $(document).ready(function () {
        $(".user-select").select2({
            width: '100%',
        });
    });

    $('.user-select').on('change', function () {
            var id = $(this).val();
            if (id) {
                window.location = @json(route('work_times.index'))+'?selectedUser='+id+'&month='+@json($month)+'&year='+@json($year);
            }
            return false;
        });

</script>

<script>

</script>
@endpush
@endsection
