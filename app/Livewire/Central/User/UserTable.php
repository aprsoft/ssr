<?php

namespace App\Livewire\Central\User;

use App\Models\Central\User;
// use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
// use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class UserTable extends PowerGridComponent
{
    public string $tableName = 'userTable';

    public string $sortField = 'created_at'; 

    public string $sortDirection = 'desc';

    public function setUp(): array
    {        

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
        return User::withTrashed()->where('id', '!=', 1);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email')          
            ->add('estado', function ($tenant) {
            if ($tenant->trashed()) {
                return '<span class="inline-flex items-center gap-1 text-red-600 font-medium">
                            <span class="text-lg">✕</span>
                            Suspendido
                        </span>';
            }

            return '<span class="inline-flex items-center gap-1 text-green-600 font-medium">
                        <span class="text-lg">✓</span>
                        Activo
                    </span>';
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable(),
                
            Column::make('Nombre', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),  
                
            Column::make('Estado', 'estado'),
               

            Column::action('Acciones')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }    

    public function actions(User $row): array
    {
        return [    
            
            Button::add('show') 
                ->icon('default-eye')               
                ->class('px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 flex items-center justify-center')
                ->route('central.users.show', ['user' => $row->id]),   

             Button::add('edit')
                ->icon('default-pencil')               
                ->class('px-2 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 flex items-center justify-center')
                ->route('central.users.edit', ['user' => $row->id]),   
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
