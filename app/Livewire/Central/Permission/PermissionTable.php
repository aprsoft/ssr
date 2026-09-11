<?php

namespace App\Livewire\Central\Permission;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use Spatie\Permission\Models\Permission;

final class PermissionTable extends PowerGridComponent
{
    public string $tableName = 'permissionTable';

    public function setUp(): array
    {
        if ($this->isRoleForm()) {
            $this->showCheckBox();
        }

        return [
            PowerGrid::header()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Permission::query()
            ->where('guard_name', 'web');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name');
    }

    public function columns(): array
    {
        $columns = [
            Column::make('Id', 'id')
                ->sortable(),

            Column::make('Permisos', 'name')
                ->sortable()
                ->searchable(),
        ];

        if (! $this->isRoleForm()) {
            $columns[] = Column::action('Acciones');
        }

        return $columns;
    }

    #[On('permission-destroy-confirmed')]
    public function destroy(int $id): void
    {
        $permission = Permission::query()
            ->where('guard_name', 'web')
            ->findOrFail($id);

        $rolesCount = $permission->roles()->count();

        $permissionPivotKey = config(
            'permission.column_names.permission_pivot_key'
        ) ?? 'permission_id';

        $directAssignmentsCount = DB::table(
            config('permission.table_names.model_has_permissions')
        )
            ->where($permissionPivotKey, $permission->id)
            ->count();

        if ($rolesCount > 0 || $directAssignmentsCount > 0) {
            $associations = [];

            if ($rolesCount > 0) {
                $associations[] = $rolesCount === 1
                    ? '1 rol'
                    : "{$rolesCount} roles";
            }

            if ($directAssignmentsCount > 0) {
                $associations[] = $directAssignmentsCount === 1
                    ? '1 asignación directa'
                    : "{$directAssignmentsCount} asignaciones directas";
            }

            session()->flash(
                'error',
                sprintf(
                    'No se puede eliminar el permiso "%s" porque está asociado a %s. Elimina primero esas asignaciones.',
                    $permission->name,
                    implode(' y ', $associations)
                )
            );

            $this->redirectRoute('central.permissions.index');

            return;
        }

        $permissionName = $permission->name;

        $permission->delete();

        session()->flash(
            'success',
            sprintf(
                'El permiso "%s" fue eliminado correctamente.',
                $permissionName
            )
        );

        $this->redirectRoute('central.permissions.index');
    }

    public function actions(Permission $row): array
    {
        if ($this->isRoleForm()) {
            return [];
        }

        return [
            Button::add('show')
                ->icon('default-eye')
                ->class(
                    'px-2 py-1 bg-green-600 text-white rounded ' .
                    'hover:bg-green-700 flex items-center justify-center'
                )
                ->route(
                    'central.permissions.show',
                    ['permission' => $row->id]
                ),

            Button::add('edit')
                ->icon('default-pencil')
                ->class(
                    'px-2 py-1 bg-gray-600 text-white rounded ' .
                    'hover:bg-gray-700 flex items-center justify-center'
                )
                ->route(
                    'central.permissions.edit',
                    ['permission' => $row->id]
                ),

            Button::add('destroy')
                ->icon('default-trash')
                ->class(
                    'px-2 py-1 bg-red-600 text-white rounded ' .
                    'hover:bg-red-700 flex items-center justify-center'
                )
                ->dispatch(
                    'open-confirm-modal',
                    [
                        'id' => $row->id,
                        'name' => $row->name,
                        'event' => 'permission-destroy-confirmed'
                    ]
                ),
        ];
    }

    private function isRoleForm(): bool
    {
        return in_array(
            Route::currentRouteName(),
            [
                'central.roles.create',
                'central.roles.edit',
            ],
            true
        );
    }
}