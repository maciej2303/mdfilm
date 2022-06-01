<div class="row border-bottom">
    <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            @if(isset($companyLogo) && $companyLogo != null)
                <img class="navbar-logo" src="{{asset($companyLogo)}}">
            @endif
        </div>
        <ul class="nav navbar-top-links navbar-right">
                @if(\App::getLocale() =='pl')
                <li>
                    <a href="{{ route('setlocale', ['locale' => 'en']) }}"><img alt="image" class="" src="{{asset('img/flags/United-Kingdom.png')}}"></a>
                </li>
                @else
                <li>
                    <a href="{{ route('setlocale', ['locale' => 'pl']) }}"><img alt="image" class="" src="{{asset('img/flags/Poland.png')}}"></a>
                </li>
                @endif
            @auth
                <li>
                    <a href="{{route('profile.edit')}}" class="d-flex align-items-center">
                        <avatar-component :user="{{auth()->user()}}"></avatar-component>
                        <span class="ml-1">{{ __('my_profile') }}</span>
                    </a>
                </li>
            @endauth
            @adminAndWorker
            <li>
                <a data-toggle="modal" data-target="#globalModalWorkTime">
                    <i class="fas fa-clock"></i> {{ __('work_time') }}
                </a>
            </li>
            @endadminAndWorker
            @auth
            <li class="dropdown show">
                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" aria-expanded="true">
                    <i class="fa fa-bell"></i>
                    @if($unreadCommentsCount > 0)
                        <span class="label label-danger">{{$unreadCommentsCount}}</span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-alerts">
                    <li>
                        <a href="{{route('projects.index')}}" class="dropdown-item">
                            <div>
                                @if($unreadCommentsCount > 0)
                                <span class="label label-danger">{{$unreadCommentsCount}}</span> {{ __('new_comments') }}
                                @else
                                {{ __('no_notifications') }}
                                @endif
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
            @endauth
            <li>
                <a class="dropdown-item" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> {{ __('logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
</div>

@push('js')
<script>
    $(document).ready(function() {
        $(".dropdown-toggle").dropdown();
    });
    </script>
@endpush
