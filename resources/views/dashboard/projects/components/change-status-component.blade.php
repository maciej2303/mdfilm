 <a href="{{route('project_element_component_versions.change_status', [$projectElementComponentVersion, App\Enums\ProjectVersionStatus::PENDING])}}"
     class="btn btn-xs btn-success btn-outline change-status-warning
     @if(App\Enums\ProjectVersionStatus::PENDING == $projectElementComponentVersion->status) d-none @endif">{{ __('pending') }}</a>
 <a href="{{route('project_element_component_versions.change_status', [$projectElementComponentVersion, App\Enums\ProjectVersionStatus::IN_PROGRESS])}}"
     class="btn btn-xs btn-warning btn-outline change-status-warning
     @if(App\Enums\ProjectVersionStatus::IN_PROGRESS == $projectElementComponentVersion->status) d-none @endif">{{ __('in_progres') }}</a>
 <a href="{{route('project_element_component_versions.change_status', [$projectElementComponentVersion, App\Enums\ProjectVersionStatus::TO_ACCEPT])}}"
     class="btn btn-xs btn-warning btn-outline change-status-warning
     @if(App\Enums\ProjectVersionStatus::TO_ACCEPT == $projectElementComponentVersion->status) d-none @endif">{{ __('to_accept') }}</a>
 <a href="{{route('project_element_component_versions.change_status', [$projectElementComponentVersion, App\Enums\ProjectVersionStatus::COMMENTS])}}"
     class="btn btn-xs btn-warning btn-outline change-status-warning
     @if(App\Enums\ProjectVersionStatus::COMMENTS == $projectElementComponentVersion->status) d-none @endif">{{ __('remarks') }}</a>
 <a href="{{route('project_element_component_versions.change_status', [$projectElementComponentVersion, App\Enums\ProjectVersionStatus::CLOSED])}}"
     class="btn btn-xs btn-success btn-outline change-status-warning
     @if(App\Enums\ProjectVersionStatus::CLOSED == $projectElementComponentVersion->status) d-none @endif">{{ __('closed') }}</a>
 <a href="{{route('project_element_component_versions.change_status', [$projectElementComponentVersion, App\Enums\ProjectVersionStatus::ACCEPTED])}}"
     class="btn btn-xs btn-primary btn-outline change-status-warning
     @if(App\Enums\ProjectVersionStatus::ACCEPTED == $projectElementComponentVersion->status) d-none @endif">{{ __('accepted') }}</a>
 <a href="{{route('project_element_component_versions.change_status', [$projectElementComponentVersion, App\Enums\ProjectVersionStatus::SUSPENDED])}}"
     class="btn btn-xs btn-danger btn-outline change-status-warning
     @if(App\Enums\ProjectVersionStatus::SUSPENDED == $projectElementComponentVersion->status) d-none @endif">{{ __('suspended') }}</a>
 <a href="{{route('project_element_component_versions.change_status', [$projectElementComponentVersion, App\Enums\ProjectVersionStatus::CANCELED])}}"
     class="btn btn-xs btn-danger btn-outline change-status-warning
     @if(App\Enums\ProjectVersionStatus::CANCELED == $projectElementComponentVersion->status) d-none @endif">{{ __('canceled') }}</a>