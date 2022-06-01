<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Utils\TranslationService;

class BoardController extends Controller
{
    public function index()
    {
        $statusesTranslaction = (new translationservice('project_statuses'))->getforVueArray();
        $session_filters = $this->getBoardSessionFilters();
        $projectStatuses = ProjectStatus::with(['projects.managers', 'projects.teamMembers', 'projects' => function ($query) use ( $session_filters) {
            $query = $this->parametersQuery($query, $session_filters);
            $query = $query->active();
        }])->orderBy('order', 'asc')->get()
            ->each(function ($item, $key) {
                $item->projects->each(function ($item) {
                    $item->unreadCommentsCount = $item->unreadCommentsCount(auth()->id());
                    $item->access = $item->doUserHaveAccess(auth()->id());
                });
            })->toJson();

        return view('dashboard.board.index')
            ->with('session_filters', json_encode($session_filters))
            ->with('projectStatuses', $projectStatuses)
            ->with('projectStatusesInFilter', ProjectStatus::getForVue())
            ->with('projects', Project::active()->orderBy('name')->get())
            ->with('clients', Client::orderBy('name')->get())
            ->with('statusesTranslaction', $statusesTranslaction)
            ->with('users', User::adminsAndWorkers()->orderBy('name')->get());
    }

    public function clientBoard()
    {
        $statusesTranslaction = (new translationservice('project_statuses'))->getforVueArray();

        $session_filters = $this->getBoardClientsSessionFilters();
        $clients = Client::with('projects.managers', 'projects.teamMembers', 'projects.projectStatus')->whereHas('projects', function ($query) use ($session_filters) {
            $query = $this->parametersQuery($query, $session_filters);
            $query = $query->active()->orderBy('order', 'asc');
        })->with(['projects' => function($query) {
            return $query->active();
        }])->orderBy('order', 'asc')->get()
            ->each(function ($item, $key) {
                $item->projects->each(function ($item) {
                    $item->unreadCommentsCount = $item->unreadCommentsCount(auth()->id());
                    $item->access = $item->doUserHaveAccess(auth()->id());
                });
            })->toJson();

        return view('dashboard.board.client-board')
            ->with('session_filters', json_encode($session_filters))
            ->with('clients', $clients)
            ->with('projectStatuses', ProjectStatus::getForVue())
            ->with('projects', Project::active()->orderBy('name')->get())
            ->with('clientsInFilter', Client::orderBy('name')->get())
            ->with('users', User::adminsAndWorkers()->orderBy('name')->get())
            ->with('statusesTranslaction', $statusesTranslaction);
    }
    private function getBoardSessionFilters()
    {
        if (session()->get('board_filters')) {
            return session()->get('board_filters');
        }
        return ["projectStatusFilter" => [],
                "projectFilter" => [],
                "clientFilter" => [],
                "userFilter" => [],
                "priorityFilter" => [],
                ];
    }
    private function getBoardClientsSessionFilters()
    {
        if (session()->get('board_clients_filters')) {
            return session()->get('board_clients_filters');
        };
        return ["projectStatusFilter" => [],
                "projectFilter" => [],
                "clientFilter" => [],
                "userFilter" => [],
                "priorityFilter" => [],
                ];
    }
    private function parametersQuery($projects, $parameters)
    {
        $projects = $projects->when(isset($parameters['projectStatusFilter']), function ($query) use ($parameters) {
            $query->whereHas('projectStatus', function ($quer) use ($parameters, $query) {
                $quer->where(function ($q) use ($parameters, $query) {
                    foreach ($parameters['projectStatusFilter'] as $parameter) {
                       $query->orWhere('id', '=', "{$parameter}");
                    }
                });
            });
        })->when(isset($parameters['projectFilter']), function ($query) use ($parameters) {
            $query->where(function ($q) use ($parameters) {
                foreach ($parameters['projectFilter'] as $parameter) {
                    $q->orWhere('name', 'like', "%{$parameter}%");
                }
            });
        })->when(isset($parameters['clientFilter']), function ($query) use ($parameters) {
            $query->whereHas('client', function ($quer) use ($parameters) {
                $quer->where(function ($q) use ($parameters) {
                    foreach ($parameters['clientFilter'] as $parameter) {
                        $q->orWhere('name', 'like', "%{$parameter}%");
                    }
                });
            });
        })->when(isset($parameters['userFilter']), function ($query) use ($parameters) {
            $query->where(function ($queryFilter) use ($parameters) {
                $queryFilter->whereHas('teamMembers', function ($quer) use ($parameters) {
                    $quer->where(function ($q) use ($parameters) {
                        foreach ($parameters['userFilter'] as $parameter) {
                            $q->orWhere('name', 'like', "%{$parameter}%");
                        }
                    });
                })->orWhereHas('managers', function ($quer) use ($parameters) {
                    $quer->where(function ($q) use ($parameters) {
                        foreach ($parameters['userFilter'] as $parameter) {
                            $q->orWhere('name', 'like', "%{$parameter}%");
                        }
                    });
                });
            });
        })->when(isset($parameters->priorityFilter), function ($query) use ($parameters) {
            $query->where(function ($q) use ($parameters) {
                foreach ($parameters['priorityFilter'] as $parameter) {
                    $q->orWhere('priority', $parameter);
                }
            });
        });
        return $projects;
    }
}
