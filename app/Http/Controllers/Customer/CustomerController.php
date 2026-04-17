<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;

class CustomerController
{
    public function index()
    {
        // return view('pages.central.dashboard');
        return view('customer.customer.dashboard',['title'=>'Inicio']);
    }
 }
