<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Models\Role;
use App\Models\User;


class UserController extends Controller
{
   
    public function index()
    {
        return view('tenant.user.index',['title'=>'Usuarios']);
    }

    public function create()
    {
        return view('tenant.user.create',['title'=>'Crear Usuario']);
    }
  
    public function edit(User $user)
    
    {
        $roles = Role::all();
        return  view('tenant.user.edit',['title'=>'Editar Usuario', 'roles'=>$roles]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return redirect()->route('tenant.user.index')->with('success', 'User updated successfully');
    }
}
