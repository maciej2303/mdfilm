@foreach($logs as $log)
    <small>{{$log->created_at->format('d.m.Y H:i')}}, {{$log->authorable->name}} <strong>{{$log->change}}</strong> "{!! $log->content !!}".</small><br>
@endforeach
