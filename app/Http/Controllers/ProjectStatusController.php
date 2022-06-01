<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStatuses\ProjectStatusCreateRequest;
use App\Models\ProjectStatus;
use Illuminate\Http\Request;
use App\Services\Utils\TranslationService;
use App\Models\Lang;

class ProjectStatusController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $translations = (new TranslationService('project_statuses'))->getArray();
        \View::share('translations', $translations);
    }
    public function index()
    {
        $projectStatuses = ProjectStatus::orderBy('order', 'asc')->get();

        return view('dashboard.admin.project-statuses.index')
            ->with('projectStatuses', $projectStatuses);
    }

    public function create()
    {
        return view('dashboard.admin.project-statuses.form');
    }

    public function store(ProjectStatusCreateRequest $request)
    {
        $projectStatus = ProjectStatus::create($request->except('name'));

        $projectStatus->order = ProjectStatus::all()->count();
        $projectStatus->save();

        $translations = new TranslationService('project_statuses', $projectStatus->id);
        $translations->saveForFields(['name'], $request);

        return redirect()->route('project_statuses.index');
    }

    public function edit(ProjectStatus $projectStatus)
    {
        return view('dashboard.admin.project-statuses.form')
            ->with('projectStatus', $projectStatus);
    }

    public function update(ProjectStatusCreateRequest $request, ProjectStatus $projectStatus)
    {
        $projectStatus->update($request->except('name'));
        $translations = new TranslationService('project_statuses', $projectStatus->id);
        $translations->saveForFields(['name'], $request);
        return redirect()->route('project_statuses.index');
    }

    public function destroy(Request $request)
    {
        try {
            $projectStatus = ProjectStatus::find($request->id);
            $projectStatus->delete();
            return response()->json(200);
        } catch (Exception $e) {

        }
    }

    public function changeOrder(Request $request)
    {
        try{
            foreach($request->order as $key => $order){
                $projectStatus = ProjectStatus::find($order['id']);
                $projectStatus->order = $key;
                $projectStatus->save();
            }
            return response()->json(200);
        } catch (Exception $e) {

        }
    }
}
