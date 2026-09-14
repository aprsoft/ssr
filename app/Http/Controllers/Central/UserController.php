<?php

namespace App\Http\Controllers\Central;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
   
    public function index()
    {
        return view('central.users.index',['title'=>'Usuarios']);
    }

    public function create()
    {
        return view('central.users.create',['title'=>'Crear Usuario']);
    }
  
    public function edit(User $user)
    {
        return  view('central.users.edit',['title'=>'Editar Usuario']);
    }

}
