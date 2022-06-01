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
                                <td class="project-status"><span class="label"
                                        style="color:white;background-color: {{$project->projectStatus->colour}}">{{$project->projectStatus->name}}</span>
                                </td>
                                <td class="project-title">
                                    <a href="{{route('projects.show', $project->id)}}">
                                        @if (($projectCommentsCount = $project->unreadCommentsCount(auth()->id())) > 0)
                                            <span class="label label-danger">{{$projectCommentsCount}}</span>
                                        @endif
                                        {{$project->name}}
                                        <br /><small>{{$project->client->name}}
                                        | {{\Carbon\Carbon::parse($project->created_at)->format('d.m.Y')}}</small>
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
                                    @admin
                                    <a type="button" class="btn btn-primary btn-xs tooltip-more"
                                        href="{{route('projects.edit', $project->id)}}" title="{{ __('edit') }}"><i
                                            class="fas fa-pen"></i></a>
                                    <a type="button" class="btn btn-danger btn-xs tooltip-more delete-warning"
                                        data-id="{{$project->id}}" data-route="{{route('projects.destroy')}}"
                                        title="{{ __('delete') }}"><i class="fas fa-trash"></i></a>
                                    @endadmin
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            {{$projects->links("pagination::bootstrap-4")}}
                        </div>
                    </div>