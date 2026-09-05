<?php

namespace App\Livewire\Central\Permission;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
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
        return [
            Column::make('Id', 'id')
                ->sortable(),

            Column::make('Permisos', 'name')
                ->sortable()
                ->searchable(),
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


