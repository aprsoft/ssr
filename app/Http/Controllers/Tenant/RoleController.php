<?php

namespace App\Http\Controllers\Tenant;

use Spatie\Permission\Models\Role;

class RoleController
{
    
    public function index()
    {
        return view('tenant.role.index', [
            'title' => 'Roles',
        ]);
    }
 
    public function create()
    {
        return view('tenant.role.create', [
            'title' => 'Crear Rol',
        ]);
    }
  
    public function show(string $id)
    {
        $role = Role::query()
            ->where('guard_name', 'tenant')
            ->with([
                'permissions' => fn ($query) => $query
                    ->where('guard_name', 'tenant')
                    ->orderBy('name'),
            ])
            ->findOrFail($id);

        return view('tenant.role.show', [
            'title' => 'Ver Rol',
            'role' => $role,
        ]);
    }
 
    public function edit(string $id)
    {
        $role = Role::query()
            ->where('guard_name', 'tenant')
            ->findOrFail($id);

        return view('tenant.role.edit', [
            'title' => 'Editar Rol',
            'role' => $role,
        ]);
    }  
}