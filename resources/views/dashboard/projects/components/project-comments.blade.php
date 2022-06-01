<div class="tabs-container">
    <div class="tabs-left">
        <ul class="nav nav-tabs">
            <li>
                <a class="nav-link {{$inner == 0 ? 'active' : ''}}"
                    href="{{route('projects.show', ['project' => $project, 'inner' => 0])}}">{{ __('comments') }}
                    @if($commentsCount > 0 && Auth::check())
                    <span class="label label-danger">{{$commentsCount}}</span>
                    @endif
                </a>
            </li>
            @adminAndWorker()
            <li>
                <a class="nav-link {{$inner == 1 ? 'active' : ''}}"
                    href="{{route('projects.show', ['project' => $project, 'inner' => 1])}}">{{ __('inside_comments') }}
                    @if($innerCommentsCount > 0 && Auth::check())
                    <span class="label label-danger">{{$innerCommentsCount}}</span>
                    @endif
                </a>
            </li>
            @endadminAndWorker
        </ul>
        <div class="tab-content ">
            <!-- KOMENTARZE -->
            <div id="tab-1" class="tab-pane {{$inner == 0 ? 'active' : ''}}">
                <div class="panel-body">
                    <form method="POST" id="externalCommentForm" action="{{route('comments.store')}}">
                        @csrf
                        <input type="hidden" name="relationable_id" value="{{$project->id}}">
                        <input type="hidden" name="relationable_type" value="{{get_class($project)}}">
                        <input type="hidden" name="inner" value="0">
                        <div class="{{$errors->has('comment') && $inner == 0 ? 'has-error' : ''}}">
                            <textarea type="text" class="form-control" name="comment">{{old('comment')}}</textarea>
                            @if ($errors->has('comment') && $inner == 0)
                            <small class="text-danger">{{$errors->first('comment')}}</small>
                            @endif
                        </div>
                        @if (auth()->check() && (auth()->user()->level == \App\Enums\UserLevel::ADMIN ||
                        auth()->user()->level == \App\Enums\UserLevel::WORKER))
                        <button type="button" class="btn btn-sm btn-primary m-t-xs m-b add-comment-warning">Dodaj
                            {{ __('comment') }}</button>
                        @else
                        <button type="submit" class="btn btn-sm btn-primary m-t-xs m-b">{{ __('add_comment') }}</button>
                        @endif

                    </form>

                    <div class="feed-activity-list">
                        @foreach ($comments as $comment)
                        <div class="feed-element">
                            <div class="media-body ">
                                <div class="well-header m-t-xs m-b-xs">
                                    <small class="float-right">
                                        {{$comment->created_at->format('d.m.Y H:i')}}
                                    </small>

                                    @if ($comment->new == 1 && Auth::check())
                                    <span class="label label-danger">{{ __('new') }}</span>
                                    @endif

                                    <strong>{{$comment->authorable->name}}</strong>
                                    @if($comment->label == "Klient" && (Auth::check() &&
                                    Auth::user()->level != \App\Enums\UserLevel::CLIENT))
                                    <span class="label label-default">{{ __('client') }}</span>
                                    @endif

                                    @if ($comment->label == "Bez konta")
                                    <span class="label label-default">{{ __('no_account') }}</span>
                                    @endif
                                    {{ __('wrote') }}:
                                </div>
                                <div class="well">
                                    {!! nl2br($comment->comment) !!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>


                </div>
            </div>
            <!-- /KOMENTARZE -->
            @adminAndWorker()
            <!-- KOMENTARZE WEWNĘTRZNE-->
            <div role="tabpanel" id="tab-2" class="tab-pane {{$inner == 1 ? 'active' : ''}}">

                            <div class=" panel-body">
                <form method="POST" action="{{route('comments.store')}}">
                    @csrf
                    <input type="hidden" name="relationable_id" value="{{$project->id}}">
                    <input type="hidden" name="relationable_type" value="{{get_class($project)}}">
                    <input type="hidden" name="inner" value="1">
                    <div class="{{$errors->has('comment') && $inner == 1 ? 'has-error' : ''}}">
                        <textarea type="text" class="form-control" name="comment">{{old('comment')}}</textarea>
                        @if ($errors->has('comment') && $inner == 1 )
                        <small class="text-danger">{{$errors->first('comment')}}</small>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary m-t-xs m-b">{{ __('add_comment') }}</button>
                </form>

                <div class="feed-activity-list">
                    @foreach ($innerComments as $comment)
                    <div class="feed-element">
                        <div class="media-body ">
                            <div class="well-header m-t-xs m-b-xs">
                                <small class="float-right">
                                    {{$comment->created_at->format('d.m.Y H:i')}}
                                </small>
                                @if ($comment->new == 1 && Auth::check())
                                <span class="label label-danger">{{ __('new') }}</span>
                                @endif

                                <strong>{{$comment->authorable->name}}</strong>
                                @if($comment->label == "Klient")
                                <span class="label label-default">{{ __('client') }}</span>
                                @endif

                                @if ($comment->label == "Bez konta")
                                <span class="label label-default">{{ __('no_account') }}</span>
                                @endif
                                {{ __('wrote') }}:
                            </div>
                            <div class="well">
                                {!! nl2br($comment->comment) !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
        @endadminAndWorker()
        <!-- /KOMENTARZE WEWNĘTRZNE-->
    </div>
</div>
