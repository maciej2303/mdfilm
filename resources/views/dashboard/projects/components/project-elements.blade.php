<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('project_elements') }}</h5>
    </div>
    <div class="ibox-content dd p-0" @adminAndWorker() id="nestable2" @endadminAndWorker>
        <ol class="dd-list">
            @foreach ($project->projectElements()->orderBy('order', 'asc')->get() as $projectElement)
            <li class="dd-item py-1" data-id="{{$projectElement->id}}">
                <div class="ibox project-elements {{$projectElement->colour() == 'warning' ? '' : 'collapsed'}} b-r-sm py-0">
                 <div class="ibox list-group-border panel-{{$projectElement->colour()}} }}">
                      <div class="ibox-title collapse-link bg-{{$projectElement->colour()}}" id="{{$projectElement->id}}" style="cursor: pointer">
                            @adminAndWorker()<a class="dd-nodrag" href="{{route('project_elements.edit', $projectElement->id)}}">@endadminAndWorker
                                <h5 class="prevent-collapse">{{$projectElement->name}}</h5>
                            @adminAndWorker()</a>@endadminAndWorker

                            <div class="ibox-tools">
                                @adminAndWorker()
                                <span><i class="fas fa-arrows-alt-v drag prevent-collapse" aria-hidden="true"></i></span>
                                @endadminAndWorker
                                <a>
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="ibox-content small-padding dd-nodrag" style="">
                            @foreach ($projectElement->componentsInOrder() as $component)
                            <p>
                                @adminAndWorker()
                                <a href="{{route('project_element_component_versions.create', ['projectElementComponent' => $component->id])}}"
                                    class="btn btn-default btn-xss tooltip-more" title="{{ __('add_verison') }}">
                                    <i class="fas fa-plus" aria-hidden="true"></i>
                                </a>
                                @endadminAndWorker
                                
                                <strong>{{__($component->name)}}</strong>
                                @if($component->externalVersionsCompleteVerisons->count() == 0 && auth()->user()->isClientLevel())
                                 @include('dashboard.projects.components.status-label', ['status' => $component->status,
                                'floatRight' => 1])
                                @endif
                                
                                @forelse ($component->versions()->orderBy('created_at', 'desc')->get() as $version)
                                @if(isset($projectElementComponentVersion) && $projectElementComponentVersion->id == $version->id)
                                <span class="current_box"></span>
                                @endif
  
                                @if($version->inner == 1)

                                
                                @adminAndWorker()
                                @if ($loop->first)
                                @include('dashboard.projects.components.status-label', ['status' => $version->status,
                                'floatRight' => 1])
                                @endif
                                
                                <br>
                                @if(isset($projectElementComponentVersion) && $projectElementComponentVersion->id == $version->id)
                                   <i class="fas fa-arrow-alt-circle-right" style="color:#007bff" aria-hidden="true"></i>
                                @endif

                                @if (($versionCommentsCount = $version->unreadCommentsCount(auth()->id())) > 0)
                                <span class="label label-danger small-label">{{$versionCommentsCount}}</span>
                                @endif
                                
                                <a class="{{$loop->first ? '' : 'version-closed'}} {{isset($projectElementComponentVersion) && $projectElementComponentVersion->id == $version->id ? 'font-weight-bold' : '' }}"
                                    href="{{route('project_element_component_versions.show', $version)}}">{{$version->version}}</a>
                                @endadminAndWorker
    
                                @else
               
                                @if((auth()->check() && !auth()->user()->isClientLevel()) ||
                                $version->youtube_url != null || $version->pdf != null)
                                    @if ($loop->first)
                                    @include('dashboard.projects.components.status-label', ['status' => $version->status,
                                    'floatRight' => 1])
                                    @endif

                                <br>
                                 @if(isset($projectElementComponentVersion) && $projectElementComponentVersion->id == $version->id)
                                    <i class="fas fa-arrow-alt-circle-right" style="color:#007bff" aria-hidden="true"></i>
                                  @endif
                                @if (auth()->check() && ($versionCommentsCount =
                                $version->unreadCommentsCount(auth()->id())) > 0)
                                <span class="label label-danger small-label">{{$versionCommentsCount}}</span>
                                @endif
                                  <a class="{{$loop->first ? '' : 'version-closed'}} {{isset($projectElementComponentVersion) && $projectElementComponentVersion->id == $version->id ? 'font-weight-bold' : '' }}"
                                    href="{{route('project_element_component_versions.show', $version)}}">{{$version->version}}</a>
                                @endif
                                @endif
                                @empty
                                    @if(!auth()->user()->isClientLevel())
                                    @include('dashboard.projects.components.status-label', ['status' => 'pending', 'floatRight'
                                        => 1])
                                    @endif
                                @endforelse

                            </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ol>
        @if(!$project->simple && Route::getCurrentRoute()->getActionName() != 'App\Http\Controllers\ProjectController@show')
            <a href="{{route('projects.show', $project->id)}}" class="btn btn-default m-2"><i class="fa fa-home" aria-hidden="true"></i> {{ __('project_site') }}</a>
        @endif
        @adminAndWorker()
            <a href="{{route('project_elements.create', $project->id)}}" class="btn btn-default @if(!(!$project->simple && Route::getCurrentRoute()->getActionName() != 'App\Http\Controllers\ProjectController@show'))  m-2 @endif">{{ __('add_element') }}</a>
        @endadminAndWorker

    </div>


</div>

@push('js')
<!-- Nestable List -->
<script src="{{asset('js/plugins/nestable/jquery.nestable.js')}}"></script>
<script src="{{asset('js/delete-modal.js')}}"></script>

<script>
    $(document).ready(function () {

        let preventCollapse = document.querySelector('.prevent-collapse');
        preventCollapse.addEventListener('click', function (event){
            event.prevent;
        })

        if($('#nestable2').val() != undefined){
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
                noDragClass: 'dd-nodrag',
                handleClass:'drag',
            }).on('change', updateOutput);

            updateOutput($('#nestable2').data('output', $('#nestable2-output')));

            $('.dd').on('change', function () {
                let order = $('.dd').nestable('serialize');
                $.ajax({
                    url: @json(route('project_elements.change_order')),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "post",
                    data: {
                        order: order
                    },
                    success: function (response) {},
                    error: function (jqXHR, textStatus, errorThrown) {}
                });
            });
        }
        $('.current_box').closest('.list-group-border').addClass('list-group-item-box');

    });
</script>
@endpush