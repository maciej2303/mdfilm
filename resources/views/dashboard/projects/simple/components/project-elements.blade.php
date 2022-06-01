<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('project_elements') }}</h5>
    </div>
    <div class="ibox-content small-padding">
        @foreach ($project->projectElements()->orderBy('created_at', 'desc')->get() as $projectElement)
        <div class="ibox project-elements {{$projectElement->colour() == 'warning' ? '' : 'collapsed'}} b-r-sm">
            <div class="ibox list-group-border panel-{{$projectElement->colour()}} }}">
            <div class="ibox-title bg-{{$projectElement->colour()}}">
                @adminAndWorker()<a class="dd-nodrag" href="{{route('project_elements.edit', $projectElement->id)}}">@endadminAndWorker
                    <h5>{{$projectElement->name}}</h5>
                @adminAndWorker()</a>@endadminAndWorker
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content small-padding" style="">
                @foreach ($projectElement->componentsInOrder() as $component)
                <p>
                    @adminAndWorker()
                    <a href="{{route('project_element_component_versions.create', ['projectElementComponent' => $component->id])}}"
                        class="btn btn-default btn-xss tooltip-more" title="Dodaj wersję">
                        <i class="fas fa-plus" aria-hidden="true"></i>
                    </a>
                    @endadminAndWorker
                    <strong>{{__($component->name)}}</strong>
                    @forelse ($component->versions()->orderBy('created_at', 'desc')->get() as $version)
                         @if(isset($projectElementComponentVersion) && $projectElementComponentVersion->id == $version->id)
                            <span class="current_box"></span>
                        @endif
                        @if($version->inner == 1)

                        @adminAndWorker()
                            @if ($loop->first)
                                @include('dashboard.projects.components.status-label', ['status' => $version->status, 'floatRight' => 1])
                            @endif
                            <br>
                            @if(isset($projectElementComponentVersion) && $projectElementComponentVersion->id == $version->id)
                                   <i class="fas fa-arrow-alt-circle-right" style="color:#007bff" aria-hidden="true"></i>
                                @endif
                            @if (($versionCommentsCount = $version->unreadCommentsCount(auth()->id())) > 0)
                                <span class="label label-danger small-label">{{$versionCommentsCount}}</span>
                            @endif
                            <a class="{{$loop->first ? '' : 'version-closed'}} {{isset($projectElementComponentVersion) && $projectElementComponentVersion->id == $version->id ? 'font-weight-bold' : '' }}" href="{{route('project_element_component_versions.show', $version)}}">{{$version->version}}</a>
                        @endadminAndWorker

                        @else
                            @if ($loop->first)
                                @include('dashboard.projects.components.status-label', ['status' => $version->status, 'floatRight' => 1])
                            @endif
                            <br>
                            @if(isset($projectElementComponentVersion) && $projectElementComponentVersion->id == $version->id)
                                   <i class="fas fa-arrow-alt-circle-right" style="color:#007bff" aria-hidden="true"></i>
                                @endif
                            @if (auth()->check() && ($versionCommentsCount = $version->unreadCommentsCount(auth()->id())) > 0)
                                <span class="label label-danger small-label">{{$versionCommentsCount}}</span>
                            @endif
                            <a class="{{$loop->first ? '' : 'version-closed'}} {{isset($projectElementComponentVersion) && $projectElementComponentVersion->id == $version->id ? 'font-weight-bold' : '' }}" href="{{route('project_element_component_versions.show', $version)}}">{{$version->version}}</a>
                        @endif
                    @empty
                    @include('dashboard.projects.components.status-label', ['status' => 'pending', 'floatRight' => 1])
                    @endforelse
                </p>
                @endforeach
            </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('js')

<script>
    $(document).ready(function () {
        $('.current_box').closest('.list-group-border').addClass('list-group-item-box');
    });
</script>
@endpush
