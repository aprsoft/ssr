<?php

namespace App\Livewire\Central\Tenant;

use App\Models\Central\Tenant;
// use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
// use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class TenantTable extends PowerGridComponent
{
    public string  $tableName = 'tenantTable';
    public ?string $status    = null;

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
        return match ($this->status) {
            'active' => Tenant::query(),
            'suspended' => Tenant::onlyTrashed(),
            default => Tenant::withTrashed(),
        };
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
                $url = $scheme . '://' . $tenant->id . '.' . $domain.'/tenant/login';

                return sprintf(
                    '<a target="_blank"
                    class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600"
                    href="%s">%s</a>',
                    $url,
                    e($tenant->id)
                );
            })
            ->add('created_at')
            ->add('status', function ($tenant) {
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
                ->sortable()
                ->searchable(),
            Column::make('Link Inquilino', 'link', 'link'),
            Column::make('Estado', 'status'),
            Column::action('Acciones')
            
            
        ];
    }


    // #[\Livewire\Attributes\On('destroy')]
    // public function destroy($tenantId): void
    // {
    //     dd($tenantId);
    // }

    // #[\Livewire\Attributes\On('suspend')]
    // public function suspend($tenantId): void
    // {
    //     $tenant = Tenant::findOrFail($tenantId);

    //     $tenant->delete();

     
    // }

    #[\Livewire\Attributes\On('suspend')]
    public function suspend(string $tenantId): void
    {
        $tenant = Tenant::findOrFail($tenantId);

        $tenant->delete();

        $this->dispatch('$refresh');
    }

    #[\Livewire\Attributes\On('restore')]
    public function restore(string $tenantId): void
    {
        $tenant = Tenant::onlyTrashed()->findOrFail($tenantId);

        $tenant->restore();

        $this->dispatch('$refresh');
    }

    // public function actions(Tenant $row): array
    // {
    //     return [  
            
    //         Button::add('show')
    //             ->icon('default-eye')               
    //             ->class('px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 flex items-center justify-center')
    //             ->route('central.tenants.show', ['tenant' => $row->id]), 

    //         Button::add('edit')
    //             ->icon('default-pencil')               
    //             ->class('px-2 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 flex items-center justify-center')
    //             ->route('central.tenants.edit', ['tenant' => $row->id]),   

    //         // Button::add('destroy')
    //         //     ->icon('default-trash')
    //         //     ->class('px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 flex items-center justify-center')
    //         //     ->dispatch('destroy', ['tenantId' => $row->id]),

    //         Button::add('suspend')
    //         ->icon('default-trash')
    //         ->class('px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 flex items-center justify-center')
    //         ->dispatch('suspend', ['tenantId' => $row->id]),
             
    //     ];
    // }

    public function actions(Tenant $row): array
    {
        $actions = [
            Button::add('show')
                ->icon('default-eye')
                ->class('px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 flex items-center justify-center')
                ->route('central.tenants.show', ['tenant' => $row->id]),
        ];

        if ($row->trashed()) {
            $actions[] = Button::add('restore')
                ->icon('default-arrow-path')
                ->class('px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center justify-center')
                ->dispatch('restore', ['tenantId' => $row->id]);
        } else {
            $actions[] = Button::add('edit')
                ->icon('default-pencil')
                ->class('px-2 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 flex items-center justify-center')
                ->route('central.tenants.edit', ['tenant' => $row->id]);

            $actions[] = Button::add('suspend')
                ->icon('default-trash')
                ->class('px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 flex items-center justify-center')
                ->dispatch('suspend', ['tenantId' => $row->id]);
        }

        return $actions;
    }
}


   

