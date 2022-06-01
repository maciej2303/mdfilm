<?php

namespace App\Http\Middleware;

use App\Enums\UserLevel;
use Closure;
use Illuminate\Http\Request;

class ProjectVersionAccess
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
        if ($request->projectElementComponentVersion->inner == 1) {
            if (auth()->check() && auth()->user()->level != UserLevel::CLIENT)
                return $next($request);
            else
                return redirect()->route('home');
        } else
            return $next($request);
    }
}
