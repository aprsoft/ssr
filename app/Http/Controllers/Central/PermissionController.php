<?php

namespace App\Http\Controllers\Central;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController
{
    public function index()
    {
        return view('central.permission.index', [
            'title' => 'Permisos',
        ]);
    }
 
    public function create()
    {
        return view('central.permission.create', [
            'title' => 'Crear Permiso',
        ]);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        $permission = Permission::query()
            ->where('guard_name', 'web')
            ->with([
                'roles' => fn ($query) => $query->orderBy('name'),
            ])
            ->findOrFail($id);

        return view('central.permission.show', [
            'title' => 'Ver Permiso',
            'permission' => $permission,
        ]);
    }

    public function edit(string $id)
    {
        $permission = Permission::query()
            ->where('guard_name', 'web')
            ->findOrFail($id);

        return view('central.permission.edit', [
            'title' => 'Editar Permiso',
            'permission' => $permission,
        ]);
    }
}