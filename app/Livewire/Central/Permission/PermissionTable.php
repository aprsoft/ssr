<?php

namespace App\Livewire\Central\Permission;

use Spatie\Permission\Models\Permission;
// use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
// use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class PermissionTable extends PowerGridComponent
{
    public string $tableName = 'permissionTable';

    public function setUp(): array
    { 

        if (Route::currentRouteName() === 'central.roles.create') {
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
        return Permission::query();
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
        if (Route::currentRouteName() === 'central.roles.create') {
            return [
                Column::make('Id', 'id'),        

                Column::make('Permisos', 'name')
                    ->sortable()
                    ->searchable(),

                Column::action('Action')
            ];
        }else {
               return [
                Column::make('Id', 'id'),        

                Column::make('Permisos', 'name')
                    ->sortable()
                    ->searchable(),
                  Column::action('Action')

            
            ];

        }
           
        
    }


    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions($row): array
{
    return [
        // tus botones
    ];
}

    // public function actions(Permission $row): array
    // {
    //     if (Route::currentRouteName() !== 'tenant.roles.create') {
    //     return [];
    // }

    // return [
    //     Button::add('edit')
    //         ->slot('Editar')
    //         ->route('tenant.permissions.edit', ['permission' => $row->id]),
    // ];
    // }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
