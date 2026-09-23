<?php

namespace App\Http\Controllers\Central;

use App\Models\Central\User;

class UserController extends Controller
{
   
    public function index()
    {
        return view('central.users.index',['title'=>'Usuarios']);
    }

     public function show(User $user)
    {
        return view('central.users.show',['title'=>'Mostrar Usuario','user'=> $user]);
    }

    public function create()
    {
        return view('central.users.create',['title'=>'Crear Usuario']);
    }
  
    public function edit(User $user)
    {
        return  view('central.users.edit',['title'=>'Editar Usuario','user'=>$user]);
    }

}
