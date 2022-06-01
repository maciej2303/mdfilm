<div class="row smaller-text m-b">
        <div class="col-lg-4">
            <strong>{{ __('project_description') }}:</strong>
            <p id="projectDescription">
                {{$project->project_description}}
            </p>
            @if(isset($projectElementComponentVersion) && $projectElementComponentVersion->inner == 0)
            <strong>{{ __('version_link') }}:</strong>
            <p>
                <a href="{{route('project_element_component_versions.show_by_url', ['hashedUrl' => $project->hashed_url, 'projectElementComponentVersion' => $projectElementComponentVersion])}}">
                    {{route('project_element_component_versions.show_by_url', ['hashedUrl' => $project->hashed_url, 'projectElementComponentVersion' => $projectElementComponentVersion])}}
                </a>
            </p>
            @else
            <strong>{{ __('project_link') }}:</strong>
            <p>
                <a
                    href="{{route('projects.show_by_url', $project->hashed_url)}}">{{route('projects.show_by_url', $project->hashed_url)}}</a>
            </p>
            @endif
        </div>
        <div class="col-lg-4">
            <dl class="row mb-0">
                <div class="col-sm-4">
                    <dt>{{ __('status') }}:</dt>
                </div>
                <div class="col-sm-8">
                    <dd class="mb-1"><span class="label"
                        style="color:white; background-color: {{$project->projectStatus->colour}}">{{$project->projectStatus->name}}</span>
                        @adminAndWorker()
                            @if($project->priority == 1)
                                <i class="fas fa-exclamation-triangle text-danger"></i>
                            @endif
                        @endadminAndWorker
                    </dd>
                </div>
            </dl>
            <dl class="row mb-0">
                <div class="col-sm-4">
                    <dt>{{ __('client') }}:</dt>
                </div>
                <div class="col-sm-8">
                    <dd class="mb-1">{{$project->client->name}}</dd>
                </div>
            </dl>
            <dl class="row mb-0">
                <div class="col-sm-4">
                    <dt>{{ __('term') }}:</dt>
                </div>
                <div class="col-sm-8">
                    <dd class="mb-1"><span class="label label-primary">{{\Carbon\Carbon::parse($project->term)->format('d.m.Y')}}</label></dd>
                </div>
            </dl>
        </div>
        <div class="col-lg-4" id="cluster_info">


            <dl class="row mb-0">
                <div class="col-sm-4">
                    <dt>{{ __('project_menager') }}:</dt>
                </div>
                <div class="col-sm-8">
                    <dd class="mb-1">
                        {{$project->managers->pluck('name')->implode(', ')}}
                    </dd>
                </div>
            </dl>
            <dl class="row mb-0">
                <div class="col-sm-4">
                    <dt>{{ __('team') }}:</dt>
                </div>
                <div class="col-sm-8">
                    <dd class="mb-1">
                        {{$project->teamMembers->pluck('name')->implode(', ')}}
                    </dd>
                </div>
            </dl>
            <dl class="row mb-0">
                <div class="col-sm-4">
                    <dt>{{ __('decision_makers') }}:</dt>
                </div>
                <div class="col-sm-8">
                    <dd class="mb-1">
                        @foreach ($project->contactPersons as $contactPerson)
                        @if ($loop->last)
                        {{$contactPerson->name}}
                        @else
                        {{$contactPerson->name}},
                        @endif
                        @endforeach
                    </dd>
                </div>
            </dl>
            @if($project->partners->isNotEmpty())
            <dl class="row mb-0">
                <div class="col-sm-4">
                    <dt>{{ __('project_partners') }}:</dt>
                </div>
                <div class="col-sm-8">
                    <dd class="mb-1">
                        @foreach ($project->partners as $partner)
                        @if ($loop->last)
                        {{$partner->name}}
                        @else
                        {{$partner->name}},
                        @endif
                        @endforeach
                    </dd>
                </div>
            </dl>
            @endif
        </div>


    </div>

@push('js')
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