<?php

namespace App\Http\Controllers\Central;
use App\Http\Controllers\Central\Controller; 

use App\Models\Central\ErrorLog;

class ErrorController extends Controller
{
    public function index()
    {
        return view('central.errors.index');
    }

    public function show(ErrorLog $errorLog)
    {
        return view('central.errors.show',compact('errorLog'));
    }
}