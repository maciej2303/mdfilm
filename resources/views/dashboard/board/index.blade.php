@extends('layouts.app')
@section('title', __('projects_board'))



@section('content')
<div class="wrapper wrapper-content animated fadeInRight pb-0">
    <projects-board-component
    :prop-project-statuses="{{$projectStatuses}}"
    :project-statuses-in-filter="{{$projectStatusesInFilter}}"
    :projects="{{$projects}}"
    :session-filters="{{$session_filters}}"
    :statuses-translaction="{{json_encode($statusesTranslaction)}}"
    :clients="{{$clients}}"
    :users="{{$users}}"
    :route-show="'{{ route('projects.show', [""]) }}'"
    >
    </projects-board-component>
</div>
@endsection

@push('js')
<script>
window._asset = '{{ asset('') }}';
</script>
@endpush
