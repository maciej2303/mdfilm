@extends('layouts.app')
@section('title', __('clients_board'))



@section('content')
<div class="wrapper wrapper-content animated fadeInRight pb-0">
    <clients-board-component
    :prop-clients="{{$clients}}"
    :project-statuses="{{$projectStatuses}}"
    :projects="{{$projects}}"
    :session-filters="{{$session_filters}}"
    :statuses-translaction="{{json_encode($statusesTranslaction)}}"
    :clients-in-filter="{{$clientsInFilter}}"
    :users="{{$users}}"
    :route-show="'{{ route('projects.show', [""]) }}'"
    >
    </clients-board-component>
</div>
@endsection

@push('js')
<script>
window._asset = '{{ asset('') }}';
</script>
@endpush
