@extends('layouts.app')
@section('title', 'Projekty')



@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">

            <div class="ibox ">
                <div class="ibox-title">
                    <h5>{{ __('archived_projects_list') }}</h5>
                    <div class="ibox-tools">
                        <a href="{{route('projects.index')}}" class="btn btn-xs btn-primary">{{ __('current') }}</a>
                    </div>
                </div>

                <div class="ibox-content">
                    <div class="row m-b">
                        <div class="col-md-10">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="Szukaj..." id="searchTerm"
                                value="{{@$searchTerm}}">
                        </div>
                        <div class="col-3 m-b-sm">
                            <label class="m-t-xs">{{ __('status') }}</label>
                            <select class="filter form-control" id="statusFilter" multiple="multiple"
                                onchange="filter()">
                                @foreach ($projectStatuses as $projectStatus)
                                 <option @if(isset($session_filters['statusFilter']) && in_array($projectStatus->name, $session_filters['statusFilter'])) selected @endif value="{{$projectStatus->name}}">{{$projectStatus->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 m-b-sm">
                            <label class="m-t-xs">{{ __('project') }}</label>
                            <select class="filter form-control" id="projectFilter" multiple="multiple"
                                onchange="filter()">
                                @foreach ($projectsInSelect as $project)
                                <option @if(isset($session_filters['projectFilter']) && in_array($project->name, $session_filters['projectFilter'])) selected @endif value="{{$project->name}}">{{$project->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 m-b-sm">
                            <label class="m-t-xs">{{ __('client') }}</label>
                            <select class="filter form-control" id="clientFilter" multiple="multiple"
                                onchange="filter()">
                                @foreach ($clients as $client)
                                <option @if(isset($session_filters['clientFilter']) && in_array($client->name, $session_filters['clientFilter'])) selected @endif value="{{$client->name}}">{{$client->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 m-b-sm">
                            <label class="m-t-xs">{{ __('team') }}</label>
                            <select class="filter form-control" id="userFilter" multiple="multiple" onchange="filter()">
                                @foreach ($users as $user)
                                <option @if(isset($session_filters['userFilter']) && in_array($user->name, $session_filters['userFilter'])) selected @endif value="{{$user->name}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="results">
                        @include('dashboard.projects.results')
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@push('js')
<script src="{{asset('js/delete-modal.js')}}"></script>


<script>
   var filter = (page = null) => {
        let searchTerm = $('#searchTerm').val();
        let statusFilter = $('#statusFilter').val();
        let projectFilter = $('#projectFilter').val();
        let clientFilter = $('#clientFilter').val();
        let userFilter = $('#userFilter').val();

        $.ajax({
            url: @json(route('projects.archived_filter')),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "get",
            data: {
                setFilter: true,
                searchTerm: searchTerm,
                statusFilter: statusFilter,
                projectFilter: projectFilter,
                clientFilter: clientFilter,
                userFilter: userFilter,
                page: page,
            },
            success: function (response) {
                $('.results').html(response);
                $('.tooltip-more').tooltip();
            },
            error: function (jqXHR, textStatus, errorThrown) {}
        });
    }

    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        filter(page);
    });
    $(document).ready(function () {

        $(".filter").select2({
            placeholder: "{{ __('filter') }}",
            tags: true,
            allowClear: true
        });

        $('#searchTerm').on('input', _.debounce(function () {
            filter();
        }, 1000));

        $('#searchTerm').on('keypress', function (e) {
            if (e.which == 13) {
                filter();
            }
        });
    });

</script>

<script>

</script>
@endpush
@endsection
