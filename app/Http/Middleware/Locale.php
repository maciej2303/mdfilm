<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Locale
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
        if(!\Session::get('locale')) {
            if($request->getPreferredLanguage() == 'pl') {
                $lang = 'pl';
            } else {
                $lang = 'en';
            }
            \App::setlocale($lang);
        }
        return $next($request);
    }
}