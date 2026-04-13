<?php

namespace App\Http\Controllers\Central;

// use Illuminate\Http\Request;

use App\Http\Controllers\Central\Controller; 

class DashboardController extends Controller
{
    public function __invoke()
    {
        // return view('pages.central.dashboard');
        return view('central.dashboards.dashboard',['title'=>'Inicio']);
    }
}
