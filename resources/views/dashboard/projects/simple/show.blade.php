@extends('layouts.app', ['companyLogo' => @$project->client->logo])
@section('title', 'Projekty')



@section('content')

<div class="wrapper wrapper-content ">

    <!--PODSUMOWANIE PROJEKTU -->
    <div class="row">
        <div class="col-lg-12">
            <div class="m-b-md">
                @adminAndWorker()
                <a href="{{route('project_element_component_versions.edit', $projectElementComponentVersion->id)}}" class="btn btn-white btn-xs float-right">
                    <i class="fas fa-pen"></i> {{ __('edit_version') }}
                </a>&nbsp;
                <a href="{{route('projects.edit', $project->id)}}" class="btn btn-white btn-xs float-right">
                    <i class="fas fa-pen"></i> {{ __('edit_project') }}
                </a>
                @endadminAndWorker

                <h2><a href="{{route('projects.show', $project->id)}}"><strong>{{$project->name}}</strong></a></h2>
                <h3>{{$projectElementComponentVersion->projectElementComponent->projectElement->name . ' > ' . __($projectElementComponentVersion->projectElementComponent->name) . ' > ' . $projectElementComponentVersion->version}}
                    @include('dashboard.projects.components.status-label', ['status' =>
                    $projectElementComponentVersion->status, 'floatRight' => 0])
                </h3>

                @adminAndWorker()
                @if($projectElementComponentVersion->active == 1 || $projectElementComponentVersion->isLastVersion())
                <strong>{{ __('change_status') }}:</strong>
                @include('dashboard.projects.components.change-status-component')
                @endif
                @endadminAndWorker
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
            <to-do-list-component :prop-tasks="{{$projectElementComponentVersion->tasks}}" :users="{{$users}}"></to-do-list-component>
            @endadminAndWorker
        </div>
        <!-- /ZAKŁADKI -->
        <!-- PRAWA STRONA -->
        <div class="col-lg-9">
            <div>
                @if($projectElementComponentVersion->inner == 0 || ($projectElementComponentVersion->inner == 1 && auth()->check() && auth()->user()->level != \App\Enums\UserLevel::CLIENT))
                    @if ($projectElementComponentVersion->youtube_url == null)
                        @include('dashboard.projects.versions.components.content')
                    @else
                    @include('dashboard.projects.components.acceptance-required-label')
                    <video-editor-component
                        ref="videoEditorComponent"
                        :project-version="{{$projectElementComponentVersion}}"
                        project-version-class="{{$projectElementComponentVersionClass}}"
                        :project="{{$project}}"
                        :versions="{{$versions}}"
                        :show-route="'{{ route('project_element_component_versions.show', ["", ""]) }}'"
                        :user="{{$user ?? 'null'}}"
                        avaliable-acceptance="{{$avaliableAcceptance == 1 ? '1' : '0'}}"
                        :disabled="{{$disabled}}">
                    </video-editor-component>
                    @endif
                @else
                {{-- <div class="tabs-container tab-content">
                    <div role="tabpanel" id="film-1" class="tab-pane active">
                        <div class="panel-body">
                            <p>{{ __('there_is_no_added_clients_version') }}</p>
                        </div>
                    </div>
                </div> --}}
                @endif
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

     $('.change-status-warning').click(function (e) {
            e.preventDefault();
            let link = $(this).attr('href');

            swal({
                    title: "{!! __('change_status_?') !!}",
                    text: "{!! __('are_you_sure_you_want_to_change_status_?') !!}",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#1ab394",
                    confirmButtonText: "{!! __('yes') !!}",
                    cancelButtonText: "{!! __('no') !!}",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function () {
                    window.location.href = link;
                });
        });
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
