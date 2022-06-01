<div class="tabs-container general-container m-b">
    <ul class="nav nav-tabs" role="tablist">
        @foreach ($versions as $version)
        <li>
            @if($version->inner == 1)
            @adminAndWorker()
            <a class="nav-link {{$version->id == $projectElementComponentVersion->id ? 'active' : ''}}"
                href="{{route('project_element_component_versions.show', ['projectElementComponentVersion' => $version, 'inner' => $version->inner])}}">
                {{$version->version}}
                <span class="label label-default">{{ __('inside') }}</span>
            </a>
            @endadminAndWorker
            @else
                <a class="nav-link {{$version->id == $projectElementComponentVersion->id ? 'active' : ''}}"
                href="{{route('project_element_component_versions.show', ['projectElementComponentVersion' => $version, 'inner' => $version->inner])}}">
                {{$version->version}}
            </a>
            @endif
        </li>
        @endforeach
    </ul>
    <div class="tab-content">
        <div role="tabpanel" id="film-1" class="tab-pane active">
            <div class="panel-body">
                @if($projectElementComponentVersion->pdf == null)
                    @if(auth()->check() && (auth()->user()->level == \App\Enums\UserLevel::WORKER || auth()->user()->level == \App\Enums\UserLevel::ADMIN))
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- ZAKŁADKA DODAWANIE FILMU -->
                                @if($projectElementComponentVersion->projectElementComponent->name == 'movie'
                                || $projectElementComponentVersion->projectElementComponent->name == 'animation'
                                || $projectElementComponentVersion->projectElementComponent->name == 'recordings')
                                <h3>Dodaj film poprzez przesłanie filmu do serwisu Youtube</h3>
                                <form method="POST" action="{{route('project_element_component_versions.store_video')}}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="storage_path" id="storage_path">
                                    <input type="hidden" name="project_element_component_version_id"
                                        value={{$projectElementComponentVersion->id}}>
                                    <div class="{{$errors->has('title') ? 'has-error' : ''}}">
                                        <label>{{ __('title') }}*</label>
                                        <input type="text" class="form-control" name="title" value="{{old('title')}}">
                                        @if ($errors->has('title'))
                                        <small class="text-danger">{{$errors->first('title')}}</small>
                                        @endif
                                    </div>
                                    <div class="{{$errors->has('description') ? 'has-error' : ''}} m-b-sm">
                                        <label>{{ __('description') }}*</label>
                                        <textarea class="form-control" name="description">{{old('description')}}</textarea>
                                        @if ($errors->has('description'))
                                        <small class="text-danger">{{$errors->first('description')}}</small>
                                        @endif
                                    </div>
                                    <div>
                                        <div id="resumable-error" style="display: none">
                                            Resumable not supported
                                        </div>
                                        <div id="resumable-drop" style="display: none">
                                            <p>
                                                <button type="button" class="btn btn-success" id="resumable-browse"
                                                    data-url="{{ url('upload') }}">{{ __('send_video') }}</button>
                                                @if ($errors->has('storage_path'))
                                                <small class="text-danger">{{$errors->first('storage_path')}}</small>
                                                @endif
                                            </p>

                                        </div>
                                        <ul id="file-upload-list" class="list-unstyled" style="display: none">
                                        </ul>
                                    </div>
                                    <button type="submit" id="video-upload-button" class="btn btn-primary">{{ __('send') }}</button>
                                </form>
                                <hr>
                                <h3>{{ __('add_video_by_youtube_link_from_youtube_service') }}</h3>
                                <form method="POST" action="{{route('project_element_component_versions.store_video_by_link')}}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="project_element_component_version_id"
                                        value={{$projectElementComponentVersion->id}}>
                                    <div class="{{$errors->has('link') ? 'has-error' : ''}} pb-2">
                                        <label>{{ __('video_link') }}*</label>
                                        <input type="text" class="form-control" name="link" value="{{old('link')}}">
                                        @if ($errors->has('link'))
                                        <small class="text-danger">{{$errors->first('link')}}</small>
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{ __('send') }}</button>
                                </form>
                                <!-- /ZAKŁADKA DODAWANIE FILMU -->
                                @else
                                <form method="POST" action="{{route('project_element_component_versions.store_pdf')}}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="project_element_component_version_id"
                                        value={{$projectElementComponentVersion->id}}>
                                    <div class="{{$errors->has('pdf') ? 'has-error' : ''}}">
                                        <label>{{ __('attachment_pdf') }}*</label>
                                        <div class="custom-file m-b-sm">
                                            <input id="inputGroupFile01" type="file" class="custom-file-input" name="pdf">
                                            <label class="custom-file-label"
                                                style="{{$errors->has('pdf') ? 'border-color: #ED5565' : ''}}"
                                                for="inputGroupFile01">{{ __('chose_file') }}</label>
                                        </div>
                                        @if ($errors->has('pdf'))
                                        <small class="text-danger">{{$errors->first('pdf')}}</small>
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{ __('send') }}</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    @else
                        <p>{{ __('no_video_added_to_version') }}</p>
                    @endif
                @else
                @include('dashboard.projects.components.pdf-viewer')
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ZAKŁADKI -->
@if($projectElementComponentVersion->pdf != null || $projectElementComponentVersion->youtube_url != null)
<div>
    <acceptance-box-component :acceptances="{{$projectElementComponentVersion->acceptances}}"
        :initial-user="{{$user ?? 'null'}}" :avaliable-acceptance="{{$avaliableAcceptance == 1 ? '1' : '0'}}"
        :project-version="{{$projectElementComponentVersion}}">
        </acceptance-box>
</div>
@endif
<div>
    <tabs-panel-component :is-project-show="false" :model="{{$projectElementComponentVersion}}"
        model-class="{{get_class($projectElementComponentVersion)}}" :initial-user="{{$user ?? 'null'}}" :disabled="{{$disabled}}" :project-id="{{$project->id}}">
    </tabs-panel-component>
</div>


<!-- /ZAKŁADKI -->
