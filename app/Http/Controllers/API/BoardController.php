<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ChangeLog;
use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\User;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function changeStatus(Request $request)
    {
        $project = Project::find($request->projectId);
        $project->project_status_id = $request->projectStatusId;
        $project->save();

        $projectStatus = ProjectStatus::find($request->projectStatusId);

        ChangeLog::create([
            'authorable_type' => User::class,
            'authorable_id' => auth()->id(),
            'change' => 'zmienił status projektu na',
            'content' => $projectStatus->name,
            'relationable_type' => ProjectStatus::class,
            'relationable_id' => $projectStatus->id,
            'model_type' => Project::class,
            'model_id' => $project->id,
        ]);

        return response('success', 200);
    }

    public function changeOrder(Request $request)
    {
        $orderIt = 0;
        foreach ($request->projectsInOrder as $order) {
            $project = Project::find($order['id']);
            $project->order = $orderIt;
            $project->save();
            $orderIt++;
        }
        return response('success', 200);
    }

    public function clientProjectsChangeOrder(Request $request)
    {
        $orderIt = 0;
        foreach ($request->projectsInOrder as $order) {
            $project = Project::find($order['id']);
            $project->clients_order = $orderIt;
            $project->save();
            $orderIt++;
        }
        return response('success', 200);
    }

    public function clientsChangeOrder(Request $request)
    {
        $orderIt = 0;
        foreach ($request->order as $order) {
            $clients = Client::find($order['id']);
            $clients->order = $orderIt;
            $clients->save();
            $orderIt++;
        }
        return response('success', 200);
    }

    public function filter(Request $request)
    {
        $this->setBoardSessionFilters($request->all());
        
        $session_filters = $this->getBoardSessionFilters();

        $projectStatuses = ProjectStatus::with(['projects.managers', 'projects.teamMembers', 'projects' => function ($query) use ($request, $session_filters) {
            $query = $this->parametersQuery($query, $session_filters);
            $query = $query->active();
        }])->orderBy('order', 'asc')->get()->each(function ($item, $key) {
            $item->projects->each(function ($item) {
                $item->unreadCommentsCount = $item->unreadCommentsCount(auth()->id());
                $item->access = $item->doUserHaveAccess(auth()->id());
            });
        });
        return response()->json([
            'projectStatuses' => $projectStatuses,
        ]);
    }

    private function setBoardSessionFilters($request_filters) 
    {
         session()->put('board_filters', $request_filters);
    }
    private function getBoardSessionFilters()
    {
        return session()->get('board_filters');
    }
    private function setBoardSClientsSessionFilters($request_filters) 
    {
         session()->put('board_clients_filters', $request_filters);
    }
    private function getBoardClientsSessionFilters()
    {
        return session()->get('board_clients_filters');
    }
    public function ClientFilter(Request $request)
    {
        $this->setBoardSClientsSessionFilters($request->all());
        
        $session_filters = $this->getBoardClientsSessionFilters();
        
        $clients = Client::with(['projects.managers', 'projects.teamMembers', 'projects.projectStatus'])->withAndWhereHas('projects', function ($query) use ($request, $session_filters) {
            $query = $this->parametersQuery($query, $session_filters);
            $query = $query->active();
        })->orderBy('order', 'asc')->get()->each(function ($item, $key) {
            $item->projects->each(function ($item) {
                $item->unreadCommentsCount = $item->unreadCommentsCount(auth()->id());
                $item->access = $item->doUserHaveAccess(auth()->id());
            });
        });
        return response()->json([
            'clients' => $clients,
        ]);
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
        })->when(isset($parameters['priorityFilter']), function ($query) use ($parameters) {
            $query->where(function ($q) use ($parameters) {
                foreach ($parameters['priorityFilter'] as $parameter) {
                    $q->orWhere('priority', $parameter);
                }
            });
        });
        return $projects;
    }
}
