@extends('layouts.app', ['companyLogo' => @$project->client->logo])
@section('title', 'Projekty')



@section('content')

<div class="wrapper wrapper-content ">

    <!--PODSUMOWANIE PROJEKTU -->
    <div class="row">
        <div class="col-lg-12">
            <div class="m-b-md">
                @adminAndWorker()
                <a href="{{route('projects.edit', $project->id)}}" class="btn btn-white btn-xs float-right"><i class="fas fa-pen"></i> {{ __('edit_project') }}</a>
                @endadminAndWorker
                <h2><a href="{{route('projects.show', $project->id)}}"><strong>{{$project->name}}</strong></a></h2>
            </div>
        </div>
    </div>
        @include('dashboard.projects.components.project-info')
    <!-- /PODSUMOWANIE PROJEKTU -->
    <div class="row">
        <!-- ZAKŁADKI -->
        <div class="col-lg-3">
            @include('dashboard.projects.components.project-elements')
            @adminAndWorker()
            <to-do-list-component :prop-tasks="{{$project->tasks}}" :users="{{$users}}"></to-do-list-component>
            @endadminAndWorker
        </div>
        <!-- /ZAKŁADKI -->
        <!-- PRAWA STRONA -->
        <div class="col-lg-9">
            <div>
                @include('dashboard.projects.components.acceptance-required-label')
                @include('dashboard.projects.components.project-element-miniatures')
                <tabs-panel-component :is-project-show="true" :model="{{$project}}" model-class="{{$projectClass}}" :initial-user="{{$user}}" :project-id="{{$project->id}}"
                    :disabled="false"></tabs-panel-component>
                </tabs-panel-component>
            </div>

        </div>
    </div>
    <!-- /PRAWA STRONA -->

</div>
    @include('dashboard.projects.components.tasks.add-modal')
    @include('dashboard.projects.components.tasks.edit-modal')
@push('js')
<script src="{{asset('js/tasks/add-modal.js')}}"></script>
<script src="{{asset('js/tasks/edit-modal.js')}}"></script>
<script src="{{asset('js/delete-modal.js')}}"></script>
@endpush
@endsection
