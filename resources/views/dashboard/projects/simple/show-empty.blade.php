@extends('layouts.app', ['companyLogo' => @$project->client->logo])
@section('title', 'Projekty')



@section('content')

<div class="wrapper wrapper-content ">

    <!--PODSUMOWANIE PROJEKTU -->
    <div class="row">
        <div class="col-lg-12">
            <div class="m-b-md">
                @admin
                <a href="{{route('projects.edit', $project->id)}}" class="btn btn-white btn-xs float-right">{{ __('edit_project') }}</a>
                @endadmin
                <h2><a href="{{route('projects.show', $project->id)}}"><strong>{{$project->name}}</strong></a></h2>
            </div>
        </div>
    </div>
    @include('dashboard.projects.components.project-info')
    <!-- /PODSUMOWANIE PROJEKTU -->
    <div class="row">
        <!-- ZAKŁADKI -->
        <div class="col-lg-3">
            @include('dashboard.projects.simple.components.project-elements')
            @adminAndWorker()
            <to-do-list-component :prop-tasks="{{$project->tasks}}" :users="{{$users}}"></to-do-list-component>
            @endadminAndWorker
        </div>
        <!-- /ZAKŁADKI -->
        <!-- PRAWA STRONA -->
        <div class="col-lg-9">
            <div class="tabs-container tab-content">
                <div role="tabpanel" id="film-1" class="tab-pane active">
                    <div class="panel-body">
                        <p>{{ __('there_is_no_added_clients_version') }}</p>
                    </div>
                </div>
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
<script>
document.addEventListener("DOMContentLoaded", function() {
    let text = urlify(document.querySelector('#projectDescription').innerHTML);
    document.querySelector('#projectDescription').innerHTML = text;
});

function urlify(text) {
var urlRegex = /(https?:\/\/[^\s]+)/g;
    return text.replace(urlRegex, function(url) {
        return '<a href="' + url + '" target="_blank">' + url + '</a>';
    })
}
</script>
@endpush
@endsection
