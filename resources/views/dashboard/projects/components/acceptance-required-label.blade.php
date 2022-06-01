@auth
    @foreach ($user->unAcceptedVersions($project->id) as $version)
    <div class="widget style2 yellow-bg m-t-none">
        <a class="text-white" href="{{route('project_element_component_versions.show', $version)}}">
            <div class="row">
                <div class="col-9">
                    <span class="font-bold">{{__($version->projectElementComponent->name)}} | Oczekuje na akceptację</span><br>
                    {{$version->projectElementComponent->projectElement->name . ' > ' . __($version->projectElementComponent->name) . ' > ' . $version->version}}

                </div>
                <div class="col-3 text-right">
                    <i class="fas fa-user-clock fa-3x"></i>
                </div>
            </div>
        </a>
    </div>
    @endforeach
@endauth