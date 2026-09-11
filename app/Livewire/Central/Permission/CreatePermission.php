<?php

namespace App\Livewire\Central\Permission;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class CreatePermission extends Component
{
    public string $permission = '';

    public function save()
    {
        $this->permission = trim($this->permission);

        $validated = $this->validate([
            'permission' => [
                'required',
                'string',
                'max:255',
                Rule::unique(
                    config('permission.table_names.permissions'),
                    'name'
                )->where(
                    fn ($query) => $query->where('guard_name', 'web')
                ),
            ],
        ], [
            'permission.required' => 'Debes ingresar el nombre del permiso.',
            'permission.unique' => 'Ya existe un permiso con ese nombre.',
        ]);

        Permission::create([
            'name' => $validated['permission'],
            'guard_name' => 'web',
        ]);

        session()->flash(
            'success',
            'Permiso creado correctamente.'
        );

        return redirect()->route('central.permissions.index');
    }

    public function render()
    {
        return view('livewire.central.permission.create-permission');
    }
}