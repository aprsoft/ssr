<?php

namespace App\Http\Controllers\Central;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('central.role.index', [
            'title' => 'Roles',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('central.role.create', [
            'title' => 'Crear Rol',
        ]);
    }

    /**
     * Store a newly created resource in storage.
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
        $role = Role::query()
            ->where('guard_name', 'web')
            ->with([
                'permissions' => fn ($query) => $query
                    ->where('guard_name', 'web')
                    ->orderBy('name'),
            ])
            ->findOrFail($id);

        return view('central.role.show', [
            'title' => 'Ver Rol',
            'role' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::query()
            ->where('guard_name', 'web')
            ->findOrFail($id);

        return view('central.role.edit', [
            'title' => 'Editar Rol',
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * La actualización real se realiza en el componente Livewire EditRole.
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