
<!-- MENU -->
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <img src="{{asset('img/logo-menu-white.png')}}" class="img-menu"/>
                <div class="logo-element">
                    <img src="{{asset('img/logo-menu-white.png')}}"/>
                </div>
            </li>
            @auth
                <li class="{{request()->is('home') ? 'active' : ''}}">
                    <a href="{{route('home')}}"><i class="fas fa-home"></i> <span class="nav-label">{{ __('dashboard') }}</span></a>
                </li>
                @adminAndWorker
                <li class="{{request()->is('work-times*') ? 'active' : ''}}">
                    <a href="{{route('work_times.index')}}"><i class="fas fa-clock"></i> <span class="nav-label">{{ __('work_time') }}</span></a>
                </li>
                <li class="{{request()->is('projects*', 'project-elements*', 'project-versions*', 'board*') ? 'active' : ''}}">
                    <a href=""><i class="fas fa-film"></i> <span class="nav-label">{{ __('projects') }}</span></a>
                    <ul class="nav nav-second-level collapse">
                        <li ><a href="{{route('projects.index')}}">{{ __('project_list') }}</a></li>
                        <li ><a href="{{route('board.index')}}">{{ __('projects_board') }}</a></li>
                        <li ><a href="{{route('board.client')}}">{{ __('clients_board') }}</a></li>
                    </ul>
                </li>
               <li class="{{request()->is('calendar*') ? 'active' : ''}}">
                    <a href="{{route('calendar.index')}}"><i class="far fa-calendar"></i> <span class="nav-label">{{ __('calendar') }}</span> </a>
                </li>
                @else
                <li class="{{request()->is('projects*', 'project-elements*', 'project-versions*') ? 'active' : ''}}">
                    <a href="{{route('projects.index')}}"><i class="fas fa-film"></i> {{ __('projects') }}</a>
                </li>
                @endadminAndWorker
                @admin
                <li class="{{request()->is('clients*') ? 'active' : ''}}">
                    <a href="{{route('clients.index')}}"><i class="far fa-building"></i> <span class="nav-label">{{ __('clients') }}</span> </a>
                </li>
                <li class="{{request()->is('users*') ? 'active' : ''}}">
                    <a href="{{route('users.index')}}"><i class="fas fa-users"></i> <span class="nav-label">{{ __('users') }}</span> </a>
                </li>
                <li class="{{request()->is('project-statuses*', 'work-time-types*', 'event-types*') ? 'active' : ''}}">
                    <a href="#"><i class="fas fa-cog"></i> <span class="nav-label">{{ __('settings') }}</span> </a>
                        <ul class="nav nav-second-level collapse">
                        <li ><a href="{{route('project_statuses.index')}}">{{ __('project_statuses') }}</a></li>
                        <li ><a href="{{route('work_time_types.index')}}">{{ __('work_time_type') }}</a></li>
                        <li ><a href="{{route('event_types.index')}}">{{ __('type_of_event') }}</a></li>
                    </ul>
                </li>
                @endadmin
            @endauth
        </ul>
        <div class="copyright">© MDfilm @php echo date('Y'); @endphp <a href="{{route('setlocale', ['locale' => 'pl'])}}">PL</a> <a href="{{route('setlocale', ['locale' => 'en'])}}">EN</a></div>

    </div>
</nav>
<!-- /MENU -->

