<?php 

namespace App\Services;
use App\Models\Lang;
use Closure;

class Locale
{
   public function handle($request, Closure $next)
   {
     $langs = Lang::getLangs();
     $raw_locale = $request->session()->get('locale');
     if (in_array($raw_locale, $langs)) {
       $locale = $raw_locale;
     }
     else $locale = 'pl';
       \App::setLocale($locale);
       return $next($request);
   }
 }