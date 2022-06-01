<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\WorkTimeType;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class WorkTimeController extends Controller
{
    public function getAllWorkTimes(Request $request)
    {
        $project = Project::find($request->projectId);

        //all workTimes logged in project
        $workTimes = $project->workTimes()->with('workTimeType', 'user')->get();
        $allHours = $workTimes->sum('logged_hours');
        $usersWithLoggedHours = [];
        $workTimesTypesWithLoggedHours = [];
        foreach ($workTimes as $workTime) {
            if (!in_array($workTime->workTimeType, $workTimesTypesWithLoggedHours)) {
                $workTimesTypesWithLoggedHours[] = $workTime->workTimeType;
            }
            if (!in_array($workTime->user, $usersWithLoggedHours)) {
                $usersWithLoggedHours[] = $workTime->user;
            }
        }

        foreach ($usersWithLoggedHours as $user) {
            $user->hours = 0;
        }

        usort($usersWithLoggedHours, function ($a, $b) {
            return ($a['name'] < $b['name']) ? -1 : 1;
        });

        $allWorkTimes = [];
        foreach ($workTimesTypesWithLoggedHours as $workTimeType) {
            $allWorkTimes[$workTimeType->name] = [];
            $data = [];
            $hoursPerRow = 0;
            foreach ($usersWithLoggedHours as $user) {
                $hours = $workTimes->where('user_id', $user->id)->where('work_time_type_id', $workTimeType->id)->sum('logged_hours');
                $user->hours += $hours;
                $hoursPerRow += $hours;
                $data['hours'] = $hours;
                $data['colour'] = $workTimeType->colour;
                $allWorkTimes[$workTimeType->name][] = $data;
            }
            $allWorkTimes[$workTimeType->name][] = ['hours' => $hoursPerRow, 'colour' => null];
        }
        return response()->json([
            'allWorkTimes' => $allWorkTimes,
            'users' => $usersWithLoggedHours,
            'allLoggedHours' => $allHours,
        ]);
    }
    public function getWorkTimesPerMonth(Request $request)
    {
        $project = Project::find($request->projectId);
        $month = $request->month;
        $year = $request->year;

        if ($month == 0) {
            $month = 12;
            $year--;
        }
        if ($month == 13) {
            $month = 1;
            $year++;
        }

        //all workTimes logged in project
        $workTimes = $project->workTimes()->whereMonth('date', $month)
            ->whereYear('date', $year)->with('workTimeType', 'user')->get();
        $allHours = $workTimes->sum('logged_hours');
        $usersWithLoggedHours = [];
        foreach ($workTimes as $workTime) {
            if (!in_array($workTime->user, $usersWithLoggedHours)) {
                $usersWithLoggedHours[] = $workTime->user;
            }
        }

        foreach ($usersWithLoggedHours as $user) {
            $user->hours = 0;
        }

        usort($usersWithLoggedHours, function ($a, $b) {
            return ($a['name'] < $b['name']) ? -1 : 1;
        });

        $firstDayOfMonth = Carbon::create()->month($month)->year($year)->startOfMonth();
        $lastDayOfMonth =  Carbon::create()->month($month)->year($year)->endOfMonth();
        $carbonDays = CarbonPeriod::create($firstDayOfMonth, $lastDayOfMonth)->toArray();

        $days = [];

        //get all days in month with workTimes
        foreach ($carbonDays as $day) {
            $dayProjects = [];
            $dayHours = 0;
            $days[$day->format('d.m.Y')] = [];
            foreach ($usersWithLoggedHours as $user) {
                $userDayWorkTimes = $workTimes->where('date', $day->format('Y-m-d'))
                    ->where('user_id', $user->id);
                $data = [];
                if ($userDayWorkTimes->isNotEmpty()) {
                    $data['hours'] = $userDayWorkTimes->sum('logged_hours');
                    $user->hours += $data['hours'];
                    $data['dayWorkTimes'] = $userDayWorkTimes;
                    $dayHours += $data['hours'];
                } else {
                    $data['hours'] = 0;
                    $data['dayWorkTimes'] = [];
                }
                $dayProjects[] = $data;
            }
            $days[$day->format('d.m.Y')]['dayWorkTimes'] = $dayProjects;
            $days[$day->format('d.m.Y')]['dayHours'] = $dayHours;
        }
        //summary
        foreach ($usersWithLoggedHours as $user) {
            $workTimeTypes = [];
            foreach ($workTimes->where('user_id', $user->id) as $workTime) {
                $key = $workTime->workTimeType->name;
                $data = [];
                if (!isset($workTimeTypes[$key])) {
                    $data['hours'] = $workTime->logged_hours;
                    $data['colour'] = $workTime->workTimeType->colour;
                    $workTimeTypes[$key] = $data;
                } else
                    $workTimeTypes[$key]['hours'] += $workTime->logged_hours;
            }
            $user->workTimeTypes = $workTimeTypes;
        }

        return response()->json([
            'workTimesDays' => $days,
            'month' => $month,
            'year' => $year,
            'monthAllHours' => $allHours,
            'monthAllUsers' => $usersWithLoggedHours,
        ]);
    }
}
