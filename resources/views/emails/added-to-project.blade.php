@component('mail::message')
Dzień dobry/Hello,

dodano Cię do projektu/You were added to project: {{$project->name}}.\
Bezpośredni adres do projektu/Direct link to the project: <a href="{{route('projects.show_by_url', $project->hashed_url)}}">{{route('projects.show_by_url', $project->hashed_url)}}</a>.\
Zapraszamy do wprowadzania swoich uwag i komentarzy po zalogowaniu się swoimi danymi użytkownika / Please provive your comments after logging in to our app

Pozdrawiamy/Kind regards,\
MDfilm
@endcomponent
