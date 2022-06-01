<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProjectUrlAccess
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
        if (auth()->user() != null || session('temporaryUser') != null)
            return $next($request);
        if($request->projectElementComponentVersion)
            return redirect()->route('project.login', ['hashedUrl' => $request->hashedUrl, 'projectElementComponentVersion' => $request->projectElementComponentVersion->id]);

        return redirect()->route('project.login', $request->hashedUrl);
    }
}
