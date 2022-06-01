<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $projects = auth()->user()->getAllUserProjects()->whereNull('archived_at');
        $projects = $projects->sortBy('order')->sortBy(function ($query) {
            return $query->projectStatus->order;
        });
        return view('home')->with('projects', $projects);
    }
}
