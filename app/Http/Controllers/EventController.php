<?php

namespace App\Http\Controllers;

use App\Http\Requests\Calendar\EventCreateRequest;
use App\Http\Requests\Calendar\EventEditRequest;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;
use Session;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $eventTypes = EventType::orderBy('order', 'asc')->get();
        $projects = Project::active()->get();
        $users = User::adminsAndWorkers()->get();

        $month = (int) $request->query('month',  Carbon::now()->month);
        $year = $request->query('year',  Carbon::now()->year);

        $session_filters = $this->getSessionFilters();

        if ($request->ajax()) {
            $request_filters = $request->except(['start', 'end', '_']);
            $this->setSessionFilters($request_filters);
            $session_filters = $this->getSessionFilters();

            $query =  Event::with('members')->when(isset($session_filters['eventTypeFilter']), function ($query) use ($request, $session_filters) {
                $query->whereIn('event_type_id', $session_filters['eventTypeFilter']);
            })->when(isset($session_filters['projectFilter']), function ($query) use ($request, $session_filters) {
                $query->whereIn('project_id', $session_filters['projectFilter']);
            })->when(isset($session_filters['userFilter']), function ($query) use ($request, $session_filters) {
                foreach ($session_filters['userFilter'] as $user) {
                    $query->whereHas('members', function ($q) use ($user) {
                        $q->where('user_id', $user);
                    });
                }
            });

            $startEvents = $query->whereDate('start', '>=', $request->start)
                ->whereDate('start', '<=', $request->end)
                ->get();

            $endEvents = $query->whereDate('end', '<=', $request->end)
                ->whereDate('end', '>=', $request->start)
                ->get();

            $events = $startEvents->merge($endEvents);

            $data = [];
            foreach ($events as $event) {
                $object = new stdClass();
                $object->id = $event->id;
                $object->title = $event->event;
                $object->start = $event->start;
                $object->color = $event->eventType->colour;
                $object->toggle = 'modal';
                $object->target = '#showEvent';
                $object->users = $event->users;
                $object->event = $event;
                $object->hours = $event->hours;
                $object->members = $event->members;
                if ($event->end)
                    $object->end = $event->end;
                $data[] = $object;
            }
            return response()->json($data);
        }
        $date = Carbon::create()->month($month)->year($year)->startOfMonth();
        return view('dashboard.calendar.index')->with([
            'eventTypes' => $eventTypes,
            'projects' => $projects,
            'users' => $users,
            'date' => $date,
            'session_filters' => $session_filters
        ]);
    }
    private function setSessionFilters($request_filters) 
    {
         session()->put('events_filters', $request_filters);
    }
    private function getSessionFilters()
    {
        return session()->get('events_filters');
    }

    public function store(EventCreateRequest $request)
    {
        $start = Carbon::createFromFormat('d.m.Y H:i',  $request->start_date . " " . ($request->start_time ?? '00:00'));
        $end = $request->end_date != null ? Carbon::createFromFormat('d.m.Y H:i',  $request->end_date . " " . ($request->end_time ?? '23:59')) : null;
        $event = Event::create(array_merge($request->input(), ['start' => $start, 'end' => $end]));
        $event->members()->attach($request->members);
        return redirect(back()->getTargetUrl());
    }

    public function update(Event $event, EventEditRequest $request)
    {
        $start = Carbon::createFromFormat('d.m.Y H:i',  $request->start_date_edit . " " . ($request->start_time_edit ?? '00:00'));
        $end = $request->end_date_edit != null ? Carbon::createFromFormat('d.m.Y H:i',  $request->end_date_edit . " " . ($request->end_time_edit ?? '23:59')) : null;
        $event->start = $start;
        $event->end = $end;
        $event->start_time = $request->start_time_edit;
        $event->end_time = $request->end_time_edit;
        $event->event = $request->event_edit;
        $event->description = $request->description_edit;
        $event->event_type_id = $request->event_type_id_edit;
        $event->project_id = $request->project_id_edit;
        $event->save();
        $event->members()->sync($request->members_edit);
        return redirect(back()->getTargetUrl());
    }

    public function getProjectMembers(Request $request)
    {
        return response()->json([
            'members' => Project::find($request->project_id)->workers(),
        ]);
    }

    public function destroy(Request $request)
    {
        try {
            $event = Event::find($request->id);
            $event->delete();
            return response()->json(200);
        } catch (Exception $e) {
        }
    }
}
