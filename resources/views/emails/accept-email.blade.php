@component('mail::message')

{{$date}} > {{$userName }} zaakceptował wersję/accepted version {{ $projectElementComponentVersion->projectElementComponent->projectElement->name }} > {{ __( $projectElementComponentVersion->projectElementComponent->name, [], 'pl') }} > {{$projectElementComponentVersion->version}}  w projekcie/in project  {{ $projectElementComponentVersion->projectElementComponent->projectElement->project->name}}

Pozdrawiamy/Kind regards,\
MDfilm
@endcomponent
