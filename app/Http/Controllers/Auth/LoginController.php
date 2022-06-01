<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserLevel;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if (!$user->isActive()) {
            auth()->logout();
            toastr()->error('Twoje konto ma status nieaktywny.');
            return redirect('/login');
        }
        $user->last_login_at = now();
        $user->save();
        if ($request->hashedUrl != null) {
            session(['projectAccess' => $request->hashedUrl]);
            if($request->projectElementComponentVersionId != null) {
                return redirect()->route('project_element_component_versions.show', $request->projectElementComponentVersionId);
            }
            return redirect()->route('projects.show_by_url', $request->hashedUrl);
        }
    }
    public function logout(Request $request)
    {
        $locale =  \Session::get('locale');
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request, $locale) ?: redirect('/');
    }

    protected function loggedOut(Request $request, $locale)
    {
        \Session::put('locale',$locale);
    }
}
