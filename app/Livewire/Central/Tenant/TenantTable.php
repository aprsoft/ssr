<?php

namespace App\Livewire\Central\Tenant;

use App\Models\Tenant;
// use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
// use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
// use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class TenantTable extends PowerGridComponent
{
    public string $tableName = 'tenantTable';

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
        return Tenant::query();
    }

 

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            // ->add('link', function ($tenant) {
            //     $url = 'http://' . $tenant->id . '.ssr.test';
            //     return sprintf(
            //         '<a target="_blank"
            //         class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600"
            //         href="%s">%s</a>',
            //         $url,
            //         $tenant->id
            //     );
            // })

            ->add('link', function ($tenant) {
                $scheme = env('APP_DEBUG')? 'http' : 'https';
                $domain = env('APP_DEBUG')? 'ssr.test' : 'aprsoft.cl';
                $url = $scheme . '://' . $tenant->id . '.' . $domain;

                return sprintf(
                    '<a target="_blank"
                    class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600"
                    href="%s">%s</a>',
                    $url,
                    e($tenant->id)
                );
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),
            Column::make('Link Inquilino', 'link', 'link'),
            
            
        ];
    }
}


   

