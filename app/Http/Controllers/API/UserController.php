<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectElementComponentVersion;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        $project = $request->modelClass == Project::class ? Project::find($request->modelId) : ProjectElementComponentVersion::find($request->modelId)->project();
        $allProjectUsers = $project->allMembers();
        $innerProjectUsers = $project->workers();
        return response()->json([
            'users' => $allProjectUsers,
            'innerUsers' => $innerProjectUsers,
        ]);
    }
}
