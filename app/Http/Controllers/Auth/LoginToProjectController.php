<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TemporaryUser;
use Illuminate\Http\Request;
use Auth;

class LoginToProjectController extends Controller
{
    public function loginToProject($hashedUrl, $projectElementComponentVersionId = null)
    {
        return view('auth.link-login')->with('hashedUrl', $hashedUrl)->with('projectElementComponentVersionId', $projectElementComponentVersionId);
    }

    public function LoginByName(Request $request)
    {
        $request->validate([
            'emailTemporary' => 'required|max:255|unique:users,email|min:3|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
        ]);
        $temporaryUser = TemporaryUser::create(['name' => $request->name, 'email' => $request->emailTemporary, 'login_ip' => $request->getClientIp()]);
        session(['temporaryUser' => $temporaryUser]);
        session(['projectAccess' => $request->hashedUrl]);
        if ($request->projectElementComponentVersionId) {
            return redirect()->route('project_element_component_versions.show', $request->projectElementComponentVersionId);
        }
        return redirect()->route('projects.show_by_url', $request->hashedUrl);
    }
}
