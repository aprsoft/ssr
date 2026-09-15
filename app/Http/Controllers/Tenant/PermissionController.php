<?php

namespace App\Http\Controllers\Tenant;

use Spatie\Permission\Models\Permission;

class PermissionController
{
    public function index()
    {
        return view('tenant.permission.index', [
            'title' => 'Permisos',
        ]);
    }
 
    public function create()
    {
        return view('tenant.permission.create', [
            'title' => 'Crear Permiso',
        ]);
    }



    public function show(string $id)
    {
        $permission = Permission::query()
            ->where('guard_name', 'tenant')
            ->with([
                'roles' => fn ($query) => $query->orderBy('name'),
            ])
            ->findOrFail($id);

        return view('tenant.permission.show', [
            'title' => 'Ver Permiso',
            'permission' => $permission,
        ]);
    }

    public function edit(string $id)
    {
        $permission = Permission::query()
            ->where('guard_name', 'tenant')
            ->findOrFail($id);

        return view('tenant.permission.edit', [
            'title' => 'Editar Permiso',
            'permission' => $permission,
        ]);
    }
}