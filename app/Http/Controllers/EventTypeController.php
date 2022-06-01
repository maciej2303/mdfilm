<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventTypes\EventTypeCreateRequest;
use App\Models\EventType;
use Illuminate\Http\Request;
use App\Services\Utils\TranslationService;
use App\Models\Lang;
class EventTypeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $translations = (new TranslationService('event_types'))->getArray();
        \View::share('translations', $translations);
    }
    public function index()
    {
        $lang = \App::getLocale();
        $eventTypes = EventType::orderBy('order', 'asc')->get();
        $translations = (new TranslationService('event_types'))->getArray();
        
        return view('dashboard.admin.event-types.index')
            ->with('eventTypes', $eventTypes)
            ->with('lang',$lang)
            ->with('translations', $translations);
    }

    public function create()
    {
        return view('dashboard.admin.event-types.form');
    }

    public function store(EventTypeCreateRequest $request)
    {
        $eventType = EventType::create($request->except('name'));
        $eventType->order = EventType::all()->count();
        $eventType->save();

        $translations = new TranslationService('event_types', $eventType->id);
        $translations->saveForFields(['name'], $request);

        return redirect()->route('event_types.index');
    }

    public function edit(EventType $eventType)
    {
        return view('dashboard.admin.event-types.form')
            ->with('eventType', $eventType);
    }

    public function update(EventTypeCreateRequest $request, EventType $eventType)
    {
        $eventType->update($request->except('name'));

        $translations = new TranslationService('event_types', $eventType->id);
        $translations->saveForFields(['name'], $request);
        return redirect()->route('event_types.index');
    }

    public function destroy(Request $request)
    {
        try {
            $eventType = EventType::find($request->id);
            $eventType->delete();
            return response()->json(200);
        } catch (Exception $e) {
        }
    }

    public function changeOrder(Request $request)
    {
        try {
            foreach ($request->order as $key => $order) {
                $eventType = EventType::find($order['id']);
                $eventType->order = $key;
                $eventType->save();
            }
            return response()->json(200);
        } catch (Exception $e) {
        }
    }
}
