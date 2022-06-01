<?php

namespace App\Http\Controllers\API;

use App\Enums\ProjectVersionStatus;
use App\Enums\UserLevel;
use App\Http\Controllers\Controller;
use App\Models\ProjectElementComponent;
use App\Models\ProjectElementComponentVersion;
use App\Models\ProjectElementComponentVersionAcceptance;
use App\Models\User;
use App\Repositories\Comment\CommentRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Storage;
use App\Mail\AcceptEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Element;

class ProjectElementComponentVersionController extends Controller
{
    public function updateAcceptance(Request $request)
    {
        $acceptance = ProjectElementComponentVersionAcceptance::where('project_element_component_version_id', $request->project_element_component_version_id)
            ->where('user_id', $request->user_id)->first();
        $acceptance->acceptance = true;
        $acceptance->acceptance_date = Carbon::now();
        $acceptance->save();
        $acceptances = ProjectElementComponentVersion::find($request->project_element_component_version_id)->acceptances;
        $avaliableAcceptance = false;
        $allAccepted = true;
        $this->sendAcceptEmail($request);
        foreach ($acceptances as $acceptance) {
            $acceptance->user;
            if ($acceptance->acceptance == false)
                $allAccepted = false;
        }
        if ($allAccepted == true) {
            $projectElementComponentVersion = ProjectElementComponentVersion::find($request->project_element_component_version_id);
            $projectElementComponentVersion->status = ProjectVersionStatus::ACCEPTED;
            $projectElementComponentVersion->save();
            return response()->json([
                'refresh' => true,
            ]);
        }
        

        return response()->json([
            'acceptances' => $acceptances,
            'avaliableAcceptance' => $avaliableAcceptance,
        ]);
    }
    private function sendAcceptEmail($request) {
        if(auth()->user()->isClientLevel()) {
            $projectElementComponentVersion = ProjectElementComponentVersion::find($request->project_element_component_version_id);
            try {
                foreach($projectElementComponentVersion->projectElementComponent->projectElement->project->managers->pluck('email')->toArray() as $email) {
                    Mail::to($email)->send(new AcceptEmail($projectElementComponentVersion));
                }
            } catch (\Throwable $th) {
                Log::error($th);
            }
        }
    }
}
