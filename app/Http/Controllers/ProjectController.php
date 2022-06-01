<?php

namespace App\Http\Controllers;

use App\Enums\ProjectVersionStatus;
use App\Enums\UserLevel;
use App\Helpers\CollectionHelper;
use App\Http\Requests\Projects\ProjectCreateRequest;
use App\Http\Requests\Projects\ProjectEditRequest;
use App\Mail\AddedToProjectEmail;
use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectElement;
use App\Models\ProjectElementComponent;
use App\Models\ProjectElementComponentVersion;
use App\Models\ProjectElementComponentVersionAcceptance;
use App\Models\ProjectStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->searchTerm ?? '';
        $projects = Project::active();
        $projectsInSelect = Project::active()->orderBy('name')->get();

        if ($request->setFilter) {
            $request_filters = $request->except(['searchTerm', 'page', 'setFilter']) ? $request->except(['searchTerm', 'page']) : null;
                $this->setSessionFilters($request_filters);
        }

        $session_filters = $this->getSessionFilters();
        if ($request->ajax()) {
            $projects = $this->parametersQuery($projects, $session_filters);
            $projects = $this->searchTermQuery($projects, $searchTerm);
            $projects = $this->order($projects);
            return response()->json(view('dashboard.projects.results', compact(['projects', 'session_filters']))->render());
        }
        $projects = $this->parametersQuery($projects, $session_filters);
        $projects = $this->order($projects);
        $archivedProjectsCommentsCount = 0;
        if (auth()->check() && auth()->user()->level != UserLevel::CLIENT) {
            $archivedProjects = Project::inactive()->get();
            foreach ($archivedProjects as $project) {
                $archivedProjectsCommentsCount += $project->unreadCommentsCount(auth()->id());
            }
        }
        return view('dashboard.projects.index')
            ->with('session_filters', $session_filters)
            ->with('projects', $projects)
            ->with('searchTerm', $searchTerm)
            ->with('archivedProjectsCommentsCount', $archivedProjectsCommentsCount)
            ->with('projectsInSelect', $projectsInSelect)
            ->with('projectStatuses', ProjectStatus::getSortedStauses())
            ->with('clients', Client::orderBy('name')->get())
            ->with('users', User::adminsAndWorkers()->orderBy('name')->get());
    }

    private function setSessionFilters($request_filters) 
    {
         session()->put('project_filters', $request_filters);
    }
    private function getSessionFilters()
    {
        return session()->get('project_filters');
    }
    public function archivedProjects(Request $request)
    {
        $projects = Project::archived();
        $projectsInSelect = Project::archived()->orderBy('name')->get();
        $searchTerm = $request->searchTerm;
        if ($request->ajax()) {
            $projects = $this->parametersQuery($projects, $request);
            $projects = $this->searchTermQuery($projects, $searchTerm);
            $projects = $this->order($projects);
            return response()->json(view('dashboard.projects.archive.results', compact('projects'))->render());
        }

        $projects = $this->order($projects);

        return view('dashboard.projects.archive.index')
            ->with('projects', $projects)
            ->with('searchTerm', $searchTerm)
            ->with('projectsInSelect', $projectsInSelect)
            ->with('projectStatuses', ProjectStatus::getSortedStauses())
            ->with('clients', Client::orderBy('name')->get())
            ->with('users', User::adminsAndWorkers()->orderBy('name')->get());
    }

    private function order($projects)
    {
        if (auth()->user()->level == UserLevel::CLIENT) {
            $projects = $projects->whereHas('contactPersons', function ($query) {
                $query->where('contact_person_id', auth()->id());
            })->orWhereHas('partners', function ($query) {
                $query->where('partner_id', auth()->id());
            });
        }
        $projects = $projects->get()->sortBy('order')->sortBy(function ($query) {
            return $query->projectStatus->order;
        });
        $projects = CollectionHelper::paginate($projects, 25);

        return $projects;
    }
    private function parametersQuery($projects, $parameters)
    {
        $projects = $projects->when(isset($parameters['statusFilter']), function ($query) use ($parameters) {
            $query->whereHas('projectStatus', function ($quer) use ($parameters) {
                $quer->where(function ($q) use ($parameters) {
                    foreach ($parameters['statusFilter'] as $parameter) {
                        $q->orWhere('name', 'like', "%{$parameter}%");
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
    private function searchTermQuery($projects, $searchTerm)
    {
        if (auth()->user()->level == UserLevel::CLIENT) {
            $projects = $projects->whereHas('contactPersons', function ($query) {
                $query->where('contact_person_id', auth()->id());
            });
        }
        $projects = $projects->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%')
                ->orWhereRaw("DATE_FORMAT(term, '%d.%m.%Y') like '" . '%' . $searchTerm . "%'")
                ->orWhereRaw("DATE_FORMAT(created_at, '%d.%m.%Y') like '" . '%' . $searchTerm . "%'")
                ->orWhereHas('teamMembers', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('client', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('managers', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('projectStatus', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                });
        });
        return $projects;
    }
    public function create()
    {
        $workers = User::adminsAndWorkers()->orderBy('name')->get();
        $contacts = User::clients()->orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        $partners = User::clients()->with('client')->orderBy('name')->get();
        $project_statuses = ProjectStatus::orderBy('order', 'asc')->get();
        return view('dashboard.projects.form')
            ->with('workers', $workers)
            ->with('clients', $clients)
            ->with('contacts', $contacts)
            ->with('partners', $partners)
            ->with('project_statuses', $project_statuses);
    }

    public function show(Project $project)
    {
        return $this->showProject($project);
    }

    public function showByUrl($url)
    {
        $project = Project::where('hashed_url', $url)->first();
        return $this->showProject($project);
    }

    private function showProject(Project $project)
    {
        $inner = 0;
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->level != UserLevel::CLIENT)
                $inner = 1;
        } else {
            $user = 'null';
        }
        if ($project->simple) {
            $projectElementComponentVersion = $project->getLatestVersionInSimpleProject($inner);
            if ($projectElementComponentVersion != null) {
                $projectElementComponentVersion->simple = 1;
                $disabled = $projectElementComponentVersion->projectElementComponent->versions()->latest()->first()->id == $projectElementComponentVersion->id ? 'false' : 'true';
                $projectElementComponentVersion->youtube_url = str_replace('https://www.youtube.com/embed/', '', $projectElementComponentVersion->youtube_url);
                $avaliableAcceptance = false;
                foreach ($projectElementComponentVersion->acceptances as $acceptance) {
                    $acceptance->user;
                    if ($user != 'null' && $acceptance->user->id == $user->id && $acceptance->acceptance == false)
                        $avaliableAcceptance = true;
                }

                $versions = $projectElementComponentVersion->projectElementComponent->versions()->orderBy('created_at', 'desc')->get();

                if ($projectElementComponentVersion->pdf != null) {
                    $projectElementComponentVersion->pdf = FacadesStorage::disk('s3')->temporaryUrl(
                        $projectElementComponentVersion->pdf,
                        Carbon::now()->addMinutes(60)
                    );
                }

                return view('dashboard.projects.simple.show')
                    ->with('project', $project)
                    ->with('projectElementComponentVersionClass', get_class($projectElementComponentVersion))
                    ->with('user', $user)
                    ->with('versions', $versions)
                    ->with('avaliableAcceptance', $avaliableAcceptance)
                    ->with('disabled', $disabled)
                    ->with('users', $project->allMembers())
                    ->with('projectElementComponentVersion', $projectElementComponentVersion);
            } else {
                return view('dashboard.projects.simple.show-empty')
                    ->with('project', $project)
                    ->with('users', $project->allMembers());
            }
        }

        return view('dashboard.projects.show')
            ->with('project', $project)
            ->with('projectClass', get_class($project))
            ->with('user', $user)
            ->with('users', $project->allMembers());
    }

    public function store(ProjectCreateRequest $request)
    {
        $project = Project::create(array_merge($request->all(), ['hashed_url' => (string) Str::uuid()]));
        $project->order = $project->projectStatus->projects->count();
        $project->save();
        $project->contactPersons()->attach($request->contact_persons);
        $project->managers()->attach($request->managers);
        $project->teamMembers()->attach($request->team_members);
        $project->partners()->attach($request->partners);
        //Creating simple project with default one element and first version.
        if ($project->simple) {
            $projectElement = ProjectElement::create([
                'name' => $project->name,
                'project_id' => $project->id,
            ]);
            $projectElementComponent = ProjectElementComponent::create(['name' => 'Film', 'project_element_id' => $projectElement->id]);
            $projectElementComponentVersion = ProjectElementComponentVersion::create([
                'project_element_component_id' => $projectElementComponent->id,
                'version' => "v.01",
                'inner' => 1,
            ]);
            $decisivePersons = $projectElementComponent->projectElement->project->managers;
            foreach ($decisivePersons as $decistionMaker) {
                ProjectElementComponentVersionAcceptance::create(['project_element_component_version_id' => $projectElementComponentVersion->id, 'user_id' => $decistionMaker->id]);
            }
        }
        try {
            Mail::to($project->contactPersons->pluck('email')->toArray())->send(new AddedToProjectEmail($project));
            Mail::to($project->partners->pluck('email')->toArray())->send(new AddedToProjectEmail($project));
        } catch (\Throwable $th) {
            Log::error($th);
        }

        return redirect()->route('projects.index');
    }

    public function edit(Project $project)
    {
        $workers = User::adminsAndWorkers()->orderBy('name')->get();
        $contacts = User::clients()->orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        $partners = User::clients()->with('client')->orderBy('name')->get();
        $project_statuses = ProjectStatus::orderBy('order', 'asc')->get();

        $url = back()->getTargetUrl();
        $route = app('router')->getRoutes($url)->match(app('request')->create($url))->getName();
        $editFromShow = false;
        if ($route == 'projects.show' || $route == 'project_element_component_versions.show') {
            $editFromShow = true;
        }
        return view('dashboard.projects.form')
            ->with('project', $project)
            ->with('workers', $workers)
            ->with('clients', $clients)
            ->with('contacts', $contacts)
            ->with('partners', $partners)
            ->with('project_statuses', $project_statuses)
            ->with('editFromShow', $editFromShow);
    }

    public function update(ProjectEditRequest $request, Project $project)
    {
        $newContactPersonsId = array_diff($request->contact_persons ?? [], $project->contactPersons()->pluck('users.id')->toArray());
        $newContactPersons = User::find($newContactPersonsId)->pluck('email')->toArray();
        $newPartners = array_diff($request->partners ?? [], $project->partners()->pluck('users.id')->toArray());
        $newPartners = User::find($newPartners)->pluck('email')->toArray();
        $newManagers = array_diff($request->managers ?? [], $project->managers()->pluck('users.id')->toArray());

        $oldContactPersons = array_diff($project->contactPersons()->pluck('users.id')->toArray(), $request->contact_persons ?? []);
        foreach ($project->getAllVersionsIdAndStatusInProject() as $version) {
            if (
                $version->status != ProjectVersionStatus::ACCEPTED
                && $version->status != ProjectVersionStatus::CLOSED
                && $version->status != ProjectVersionStatus::CANCELED
            ) {
                foreach ($newManagers as $manager) {
                    ProjectElementComponentVersionAcceptance::create(['project_element_component_version_id' => $version->id, 'user_id' => $manager]);
                }
                foreach ($newContactPersonsId as $contactPerson) {
                    ProjectElementComponentVersionAcceptance::create(['project_element_component_version_id' => $version->id, 'user_id' => $contactPerson]);
                }
                foreach($oldContactPersons as $oldContactPerson) {
                    ProjectElementComponentVersionAcceptance::where(['project_element_component_version_id' => $version->id, 'user_id' => $oldContactPerson])->delete();
                }
            }
        }
        $project->update($request->all());
        $project->contactPersons()->sync($request->contact_persons);
        $project->managers()->sync($request->managers);
        $project->teamMembers()->sync($request->team_members);
        $project->partners()->sync($request->partners);
        try {
            Mail::to($newContactPersons)->send(new AddedToProjectEmail($project));
            Mail::to($newPartners)->send(new AddedToProjectEmail($project));
        } catch (\Throwable $th) {
            Log::error($th);
        }
        if ($request->editFromShow) {
            return redirect()->route('projects.show', $project->id);
        }
        return redirect()->route('projects.index');
    }

    public function destroy(Request $request)
    {
        try {
            $project = Project::find($request->id);
            $project->delete();
            return response()->json(200);
        } catch (Exception $e) {
        }
    }

    public function archive(Project $project)
    {
        try {
            $project->archived_at = Carbon::now();
            $project->save();
            return response()->json([
                'route' => route('projects.archived'),
            ]);
        } catch (Exception $e) {
        }
    }

    public function unarchive(Project $project)
    {
        try {
            $project->archived_at = null;
            $project->save();
            return response()->json([
                'route' => route('projects.index'),
            ]);
        } catch (Exception $e) {
        }
    }

    public function changePriority(Project $project)
    {
        $project->priority = !$project->priority;
        $project->save();
        return redirect()->route('projects.index');
    }
}
