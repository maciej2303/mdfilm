<div class="row">
@foreach ($project->projectElements()->orderBy('order', 'asc')->get() as $projectElement)
    @foreach ($projectElement->componentsInOrder(false) as $component)
    <div class="col-3" style="padding-bottom:20px">
        <div class="col-12 ibox-content border-{{$projectElement->colour()}} mini "  >
            <h5 class="m-b-none">{{$projectElement->name}}</h5>
            

            @forelse ($component->versions()->orderBy('created_at', 'desc')->get() as $version)
              @if($loop->first)
                 @adminAndWorker()
                   <small>{{__($component->name)}} &gt; {{$version->version }} </small>
                   @if(in_array($component->name, ['movie', 'animation','recordings']))
                       @if($version->youtube_url != null) 
                         <div class="clicable" data-id="{{$version->id}}"></div>
                         <div style="background-position:center; background-size:cover; background-image: url('http://img.youtube.com/vi/{{$version->youtube_url}}/sddefault.jpg')" class="mini-project"></div>
                         <div style="position:absolute; left:15px; top:53px;"><small>
                             @include('dashboard.projects.components.status-label', ['status' => $version->status,
                            'floatRight' => 0])
                            </small>
                         </div>
                       @else
                         <div class="mini-project grey"><h2>FILM</h2></div>
                         <div style="position:absolute; left:15px; top:53px;"><small>
                             @include('dashboard.projects.components.status-label', ['status' => $version->status,
                            'floatRight' => 0])
                        </small>
                        </div>
                       @endif
                   @else
                       <div class="clicable" data-id="{{$version->id}}"></div>
                       <div class="mini-project"><h2>{{__($component->name)}}</h2></div>
                       <div style="position:absolute; left:15px; top:53px;"><small>
                             @include('dashboard.projects.components.status-label', ['status' => $version->status,
                            'floatRight' => 0])
                        </small>
                        </div>
                   @endif
                 @endadminAndWorker
                 @if(auth()->check() && auth()->user()->isClientLevel())
                    @if(!$version->inner && ($version->youtube_url != null || $version->pdf != null))
                       <small>{{__($component->name)}}</small>
                       @if(in_array($component->name, ['movie', 'animation','recordings']))
                          @if($version->youtube_url != null)
                              <small>&gt; {{$version->version }} </small>
                              <div class="clicable" data-id="{{$version->id}}"></div>
                              <div style="background-position:center; background-size:cover; background-image: url('http://img.youtube.com/vi/{{$version->youtube_url}}/sddefault.jpg')" class="mini-project"></div>
                              <div style="position:absolute; left:15px; top:53px;"><small>
                                 @include('dashboard.projects.components.status-label', ['status' => $version->status,
                                'floatRight' => 0])
                              </small>
                              </div>
                          @else
                              <small>&gt; {{$version->version }} </small>
                              <div class="clicable" data-id="{{$version->id}}"></div>
                              <div class="mini-project"><h2>{{__($component->name)}}</h2></div>
                              <div style="position:absolute; left:15px; top:53px;"><small>
                                @include('dashboard.projects.components.status-label', ['status' => $version->status,
                               'floatRight' => 0])
                               </small>
                              </div>
                        @endif
                      @else
                         <small>&gt; {{$version->version }} </small>
                         <div class="clicable" data-id="{{$version->id}}"></div>
                         <div class="mini-project"><h2>{{__($component->name) }}</h2></div>
                         <div style="position:absolute; left:15px; top:53px;"><small>
                                @include('dashboard.projects.components.status-label', ['status' => $version->status,
                               'floatRight' => 0])
                         </small>
                         </div>
                      @endif
                    @else
                       <small>{{__($component->name)}} </small>
                       <div class="mini-project grey"><h2>{{__($component->name)}}</h2></div>
                       <div style="position:absolute; left:15px; top:53px;"><small>
                           @include('dashboard.projects.components.status-label', ['status' => 'pending',
                           'floatRight' => 0])
                       </small>
                       </div>
                    @endif
                 @endif
               @endif
               @empty
                   <small>{{__($component->name)}} </small>
                   <div class="mini-project grey"><h2>{{__($component->name)}}</h2></div>
                        <div style="position:absolute; left:15px; top:53px;"><small>
                             @include('dashboard.projects.components.status-label', ['status' => 'pending',
                            'floatRight' => 0])
                        </small>
                    </div>
               @endforelse

        </div>
    </div>
    @endforeach
@endforeach
</div>
@push('js')
<script>
    $(document).ready(function () {
        $('.clicable').each(function() {
            self = this;
            var box = $(self).closest('.mini');
            box.css('cursor', 'pointer');
            box.data('id', $(self).data('id'));
            box.click(function() {
                var href = "../../project-versions/show/"+$(this).data('id');
                window.location.href = href;
            });
         });
    });
</script>
@endpush
