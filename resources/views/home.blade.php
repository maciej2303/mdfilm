@extends('layouts.app')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">

            <!-- MOJE PROJEKTY -->
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>{{ __('my_projects') }}</h5>
                    <div class="ibox-tools">

                    </div>
                </div>

                <div class="ibox-content">



                    <table class="table table-hover">
                        <thead style="border-bottom: 0px">
                            <th>{{ __('status') }}</th>
                            <th>{{ __('project') }}</th>
                            <th>{{ __('team') }}</th>
                            <th>{{ __('term') }}</th>
                            <th class="text-right">{{ __('options') }}</th>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                            <tr>
                                <td class="project-status">
                                    <span class="label"
                                        style="color:white;background-color: {{$project->projectStatus->colour}}">{{$project->projectStatus->name}}
                                    </span>&nbsp;
                                    @adminAndWorker()
                                        @if($project->priority == 1)
                                            <i class="fas fa-exclamation-triangle text-danger"></i>
                                        @endif
                                    @endadminAndWorker
                                </td>
                                <td class="project-title">
                                    <a href="{{route('projects.show', $project->id)}}">
                                        @if (($projectCommentsCount = $project->unreadCommentsCount(auth()->id())) > 0)
                                            <span class="label label-danger">{{$projectCommentsCount}}</span>
                                        @endif
                                        {{$project->name}}</a><br /><small>{{$project->client->name}}
                                        {{ __('|_ceated') }}:
                                        {{\Carbon\Carbon::parse($project->created_at)->format('d.m.Y')}}</small>
                                    </a>
                                </td>
                                <td>
                                    <strong>
                                        @foreach ($project->managers as $manager)
                                        {{$manager->name}},
                                        @endforeach
                                    </strong>
                                    @foreach ($project->teamMembers as $member)
                                    @if ($loop->last)
                                    {{$member->name}}
                                    @else
                                    {{$member->name}},
                                    @endif
                                    @endforeach
                                </td>
                                <td class="project-deadline">{{\Carbon\Carbon::parse($project->term)->format('d.m.Y')}}
                                </td>
                                <td class="text-right" style="min-width: 100px">
                                    <a type="button" class="btn btn-primary btn-xs tooltip-more"
                                        href="{{route('projects.show', $project->id)}}" title="{{ __('show') }}"><i
                                            class="fas fa-eye"></i></a>
                                    @adminAndWorker()
                                    <a type="button" class="btn btn-primary btn-xs tooltip-more"
                                        href="{{route('projects.edit', $project->id)}}" title="{{ __('edit') }}"><i
                                            class="fas fa-pen"></i></a>
                                    <a type="button" class="btn @if($project->priority) btn-danger @else btn-primary @endif btn-xs tooltip-more" title="@if($project->priority) {{ __('remove_priority') }} @else {{ __('set_priority') }} @endif"
                                        href="{{route('projects.change_priority', $project)}}">
                                        <i class="fas fa-exclamation-triangle" style="color:#fff"></i>
                                    </a>
                                    @endadminAndWorker
                                    @admin
                                    <a type="button" class="btn btn-danger btn-xs tooltip-more delete-warning"
                                        data-id="{{$project->id}}" data-route="{{route('projects.destroy')}}"
                                        title="{{ __('delete') }}"><i class="fas fa-trash"></i></a>
                                    @endadmin
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>




                </div>
            </div>
            <!-- /MOJE PROJEKTY -->

        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{asset('js/delete-modal.js')}}"></script>
@endpush