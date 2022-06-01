<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkTimeTypes\WorkTimeTypeRequest;
use App\Models\WorkTimeType;
use Illuminate\Http\Request;
use App\Services\Utils\TranslationService;
use App\Models\Lang;

class WorkTimeTypeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $translations = (new TranslationService('work_time_types'))->getArray();
        \View::share('translations', $translations);
    }
    public function index()
    {
        $workTimeTypes = WorkTimeType::orderBy('order', 'asc')->get();

        return view('dashboard.admin.work-time-types.index')
            ->with('workTimeTypes', $workTimeTypes);
    }

    public function create()
    {
        return view('dashboard.admin.work-time-types.form');
    }

    public function store(WorkTimeTypeRequest $request)
    {
        $workTimeType = WorkTimeType::create($request->except('name'));
        $workTimeType->order = WorkTimeType::all()->count();
        $workTimeType->save();

        $translations = new TranslationService('work_time_types', $workTimeType->id);
        $translations->saveForFields(['name'], $request);

        return redirect()->route('work_time_types.index');
    }

    public function edit(WorkTimeType $workTimeType)
    {
        return view('dashboard.admin.work-time-types.form')
            ->with('workTimeType', $workTimeType);
    }

    public function update(WorkTimeTypeRequest $request, WorkTimeType $workTimeType)
    {
        $workTimeType->update($request->except('name'));
        $translations = new TranslationService('work_time_types', $workTimeType->id);
        $translations->saveForFields(['name'], $request);
        return redirect()->route('work_time_types.index');
    }

    public function destroy(Request $request)
    {
        try {
            $workTimeType = WorkTimeType::find($request->id);
            $workTimeType->delete();
            return response()->json(200);
        } catch (Exception $e) {

        }
    }

    public function changeOrder(Request $request)
    {
        try{
            foreach($request->order as $key => $order){
                $workTimeType = WorkTimeType::find($order['id']);
                $workTimeType->order = $key;
                $workTimeType->save();
            }
            return response()->json(200);
        } catch (Exception $e) {

        }
    }
}
