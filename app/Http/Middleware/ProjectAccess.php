<?php

namespace App\Http\Middleware;

use App\Enums\UserLevel;
use Closure;
use Illuminate\Http\Request;

class ProjectAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $project = isset($request->project) ? $request->project : (isset($request->projectElementComponentVersion) ? $request->projectElementComponentVersion->project() : null);
        if (auth()->check() != null && ($project->allMembers()->contains(auth()->user()) || auth()->user()->level == UserLevel::ADMIN || auth()->user()->level == UserLevel::WORKER)) {
            return $next($request);
        }
        if (session('projectAccess') == $project->hashed_url)
            return $next($request);
        return redirect()->route('home');
    }
}
