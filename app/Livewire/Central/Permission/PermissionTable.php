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
use App\Services\Permission\DeletePermissionService;
use DomainException;

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
    public function destroy(
        DeletePermissionService $deletePermission,
        int $id
    ): void {
        try {
            $permissionName = $deletePermission->delete($id);

            session()->flash(
                'success',
                sprintf(
                    'El permiso "%s" fue eliminado correctamente.',
                    $permissionName
                )
            );
        } catch (DomainException $exception) {
            session()->flash(
                'error',
                $exception->getMessage()
            );
        }

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
                        'confirmEvent' => 'permission-destroy-confirmed',
                        'title' => 'Eliminar permiso',
                        'message' => '¿Está seguro de que desea eliminar este permiso?',
                        'warning' => 'Esta acción no se puede deshacer.',
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