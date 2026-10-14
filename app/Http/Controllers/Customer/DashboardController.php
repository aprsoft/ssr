<?php

namespace App\Http\Controllers\Customer;

// use Illuminate\Http\Request;
use App\Http\Controllers\Tenant\Controller; 

class DashboardController extends Controller
{
    public function __invoke()
    {
             
        return view('customer.dashboard.dashboard',['title' => 'Inicio']);
    }
}
