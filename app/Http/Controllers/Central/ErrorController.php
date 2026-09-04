<?php

namespace App\Http\Controllers\Central;

use App\Models\Central\ErrorLog;
use Illuminate\Http\Request;

class ErrorController
{
    public function index()
    {
        // 1. Obtener todos los usuarios de la base de datos
        $errorlogs = ErrorLog::all();

        // 2. Retornar la vista pasando los datos (ej: resources/views/central/users/index.blade.php)
        return view('central.errors.index', compact('errorlogs'));
    }
}
