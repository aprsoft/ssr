<?php

namespace App\Livewire\Central\Permission;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class EditPermission extends Component
{
    public int $permissionId;

    public string $permission = '';

    public function mount(int $permissionId): void
    {
        $permission = Permission::query()
            ->where('guard_name', 'web')
            ->findOrFail($permissionId);

        $this->permissionId = $permission->id;
        $this->permission = $permission->name;
    }

    public function update()
    {
        $permission = Permission::query()
            ->where('guard_name', 'web')
            ->findOrFail($this->permissionId);

        $this->permission = trim($this->permission);

        $validated = $this->validate([
            'permission' => [
                'required',
                'string',
                'max:255',
                Rule::unique(
                    config('permission.table_names.permissions'),
                    'name'
                )
                    ->where(
                        fn ($query) => $query
                            ->where('guard_name', 'web')
                    )
                    ->ignore($permission->id),
            ],
        ], [
            'permission.required' => 'Debes ingresar el nombre del permiso.',
            'permission.unique' => 'Ya existe un permiso con ese nombre.',
        ]);

        $permission->update([
            'name' => $validated['permission'],
        ]);

        session()->flash(
            'success',
            'Permiso actualizado correctamente.'
        );

        return redirect()->route('central.permissions.index');
    }

    public function render()
    {
        return view('livewire.central.permission.edit-permission');
    }
}