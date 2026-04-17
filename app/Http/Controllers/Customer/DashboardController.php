<?php

namespace App\Http\Controllers\Customer;

// use Illuminate\Http\Request;

use App\Http\Controllers\Customer\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // return view('pages.central.dashboard');
        $customer = Auth::guard('customer')->user();
       
        return view('customer.dashboard.dashboard',['title'=>'Inicio','customer'=>$customer]);
    }
}
