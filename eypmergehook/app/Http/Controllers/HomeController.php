<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home')
                ->with('user', Auth::user())
                ->with('repos', Auth::user()->repos->sortBy('repo_name'))
                ->with('platforms', Auth::user()->platforms->sortBy('platform_name'));
    }
}
