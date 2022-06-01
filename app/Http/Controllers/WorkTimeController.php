<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkTime\WorkTimeCreateRequest;
use App\Http\Requests\WorkTime\WorkTimeEditRequest;
use App\Models\Project;
use App\Models\User;
use App\Models\WorkTime;
use App\Models\WorkTimeType;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use stdClass;

class WorkTimeController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->userAccess($request);

        $month = (int) $request->query('month',  Carbon::now()->month);
        $year = $request->query('year',  Carbon::now()->year);
        switch ($month) {
            case 0:
                $month = 12;
                $year = $year - 1;
                return redirect(route('work_times.index') . '?selectedUser' . $user->id . '&month=' . $month . '&year=' . $year);
            case 13:
                $month = 1;
                $year = $year + 1;
                return redirect(route('work_times.index') . '?selectedUser' . $user->id . '&month=' . $month . '&year=' . $year);
        }
        $monthName = ucFirst(Carbon::create()->day(1)->month($month)->translatedFormat('F'));
        $allUserProjects = $user->getAllUserProjects();

        $userProjectsWithLoggedHours = $this->userProjectsWithLoggedHours($user, $month, $year);
        $firstDayOfMonth = Carbon::create()->month($month)->year($year)->startOfMonth();
        $lastDayOfMonth =  Carbon::create()->month($month)->year($year)->endOfMonth();
        $carbonDays = CarbonPeriod::create($firstDayOfMonth, $lastDayOfMonth)->toArray();
        $days = $this->getAllDaysWithWorkTimes($carbonDays, $userProjectsWithLoggedHours, $user);
        $projectsAndAllHours = $this->setWorkTimesTypeInProjectsAndGetAllHours($userProjectsWithLoggedHours);
        $userProjectsWithLoggedHours = $projectsAndAllHours[0];
        $allHours = $projectsAndAllHours[1];
        $users = User::adminsAndWorkers()->get();
        $workTimeTypes = WorkTimeType::orderBy('order', 'asc')->get();

        return view('dashboard.work-times.index')
            ->with('users', $users)
            ->with('selectedUser', $user)
            ->with('days', $days)
            ->with('userProjectsWithLoggedHours', $userProjectsWithLoggedHours)
            ->with('projects', $allUserProjects)
            ->with('allHours', $allHours)
            ->with('workTimeTypes', $workTimeTypes)
            ->with('month', $month)
            ->with('monthName', $monthName)
            ->with('year', $year);
    }
    private function userAccess(Request $request): User
    {
        if (auth()->user()->isAdmin())
            return $user = User::find($request->query('selectedUser',  auth()->id()));

        return $user = auth()->user();
    }

    private function userProjectsWithLoggedHours(User $user, $month, $year): Collection
    {
        $userProjects = Project::whereHas('workTimes')->with('workTimes', function ($query) use ($month, $year, $user) {
            $query->whereMonth('date', $month ?? Carbon::now()->month)
                ->whereYear('date', $year ?? Carbon::now()->year)
                ->where('user_id', $user->id)
                ->with('workTimeType');
        })->get();

        $userProjectsWithLoggedHours = $userProjects->filter(function ($filter) {
            return $filter->workTimes->count() > 0;
        });

        return $userProjectsWithLoggedHours;
    }

    private function getAllDaysWithWorkTimes($carbonDays, $userProjectsWithLoggedHours, User $user): array
    {
        $days = [];

        foreach ($carbonDays as $day) {
            $dayProjects = [];
            $dayHours = 0;
            $days[$day->format('d.m.Y')] = new stdClass();
            foreach ($userProjectsWithLoggedHours as $project) {
                $workTimes = $project->workTimes()->whereDate('date', '=', $day->format('Y-m-d'))
                    ->where('user_id', $user->id)
                    ->get();
                $projectData = [];
                if ($workTimes->isNotEmpty()) {
                    $projectData['dailyHours'] = $workTimes->sum('logged_hours');
                    $projectData['dayWorkTimes'] = $workTimes;
                    $dayHours += $projectData['dailyHours'];
                } else {
                    $projectData['dailyHours'] = 0;
                    $projectData['dayWorkTimes'] = [];
                }
                $dayProjects[$project->id] = $projectData;
            }
            $days[$day->format('d.m.Y')]->projects = $dayProjects;
            $days[$day->format('d.m.Y')]->dayHours = $dayHours;
        }

        return $days;
    }

    public function setWorkTimesTypeInProjectsAndGetAllHours($userProjectsWithLoggedHours)
    {
        $allHours = 0;
        foreach ($userProjectsWithLoggedHours as $project) {
            $project->projectHours = $project->workTimes->sum('logged_hours');
            $workTimeTypes = [];
            foreach ($project->workTimes as $workTime) {
                $key = $workTime->workTimeType->name;
                $data = [];
                if (!isset($workTimeTypes[$key])) {
                    $data['hours'] = $workTime->logged_hours;
                    $data['colour'] = $workTime->workTimeType->colour;
                    $workTimeTypes[$key] = $data;
                } else
                    $workTimeTypes[$key]['hours'] += $workTime->logged_hours;
            }
            $project->workTimeTypes = $workTimeTypes;
            $allHours += $project->projectHours;
        }
        return array($userProjectsWithLoggedHours, $allHours);
    }

    public function store(WorkTimeCreateRequest $request)
    {
        $userId = $request->user_id ?? auth()->id();
        foreach ($request->date as $key => $date) {
            $workTime = new WorkTime();
            $workTime->user_id = $userId;
            $workTime->date = Carbon::parse($date);
            $workTime->project_id = $request->project[$key];
            $workTime->work_time_type_id = $request->workTimeType[$key];
            $workTime->logged_hours = $request->logged_hours[$key];
            $workTime->task = $request->task[$key];
            $workTime->save();
        }
        $url = back()->getTargetUrl();
        $route = app('router')->getRoutes($url)->match(app('request')->create($url))->getName();
        if ($route == 'work_times.index') {
            $url = $request->ownHours == 1 ? route('work_times.index') : $url;
        }

        return redirect($url);
    }

    public function update(WorkTimeEditRequest $request, WorkTime $workTime)
    {
        $workTime->date = Carbon::parse($request->date_edit);
        $workTime->project_id = $request->project_id_edit;
        $workTime->work_time_type_id = $request->work_time_type_id_edit;
        $workTime->logged_hours = $request->logged_hours_edit;
        $workTime->task = $request->task_edit;
        $workTime->save();
        return redirect()->route('work_times.index', ['selectedUser' => $request->user_id, 'month' => $request->month, 'year' => $request->year]);
    }

    public function destroy(Request $request)
    {
        try {
            $workTime = WorkTime::find($request->id);
            $workTime->delete();
            return response()->json(200);
        } catch (Exception $e) {
        }
    }
}
