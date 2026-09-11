<?php

namespace App\Http\Controllers\Central;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('central.permission.index', [
            'title' => 'Permisos',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('central.permission.create', [
            'title' => 'Crear Permiso',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * La creación real se realiza en el componente Livewire CreatePermission.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
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

    /**
     * Show the form for editing the specified resource.
     */
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

    /**
     * Update the specified resource in storage.
     *
     * La actualización real se realiza en el componente Livewire EditPermission.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}