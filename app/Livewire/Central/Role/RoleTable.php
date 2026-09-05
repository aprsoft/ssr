<?php

namespace App\Livewire\Central\Role;

use Spatie\Permission\Models\Role;
// use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
// use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Illuminate\Support\Facades\Route;

final class RoleTable extends PowerGridComponent
{
    public string $tableName = 'roleTable';
  

    public function setUp(): array
    {
        

        $route = Route::currentRouteName();
       

       if ($route == 'central.roles.create') {
     
            $this->showCheckBox();
       } else {

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
        return Role::query();
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
            Column::make('Id', 'id'),
            Column::make('Rol', 'name')
                ->sortable(),
            Column::action('Acciones')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Role $row): array
    {
        return [
            Button::add('show')
                ->slot('Ver')
                ->id()
                ->class(
                    'pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 ' .
                    'dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 ' .
                    'dark:text-pg-primary-300 dark:bg-pg-primary-700'
                )
                ->route('central.roles.show', ['role' => $row->id]),

            Button::add('edit')
                ->slot('Editar')
                ->id()
                ->class(
                    'pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 ' .
                    'dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 ' .
                    'dark:text-pg-primary-300 dark:bg-pg-primary-700'
                )
                ->dispatch('edit', ['rowId' => $row->id]),
        ];
    }

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
