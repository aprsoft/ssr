<?php

namespace App\Http\Controllers\Tenant;

// use Illuminate\Http\Request;
use App\Http\Controllers\Tenant\Controller; 

class DashboardController extends Controller
{
    public function __invoke()
    {
             
        return view('tenant.dashboard.dashboard',['title' => 'Inicio']);
    }
}
