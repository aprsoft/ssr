<?php

namespace App\Livewire\Central\Tenant;

use App\Models\Central\Tenant;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use App\Services\Central\ErrorLog\ErrorLogger;
use App\Services\Central\Tenant\TenantLifecycleService;
use DomainException;
use Throwable;

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
   

    #[\Livewire\Attributes\On('tenant-suspend-confirmed')]
    public function suspend(
        TenantLifecycleService $tenantLifecycle,
        ErrorLogger $errorLogger,
        string $id
    ): void {
        try {
            $tenantLifecycle->suspend($id);

            session()->flash(
                'success',
                "El Tenant '{$id}' fue suspendido correctamente."
            );

            $this->redirectRoute(
                'central.tenants.index',
                ['status' =>'suspended']
            );
        } catch (DomainException $exception) {
            $errorLogger->report($exception, [
                'operation' => 'tenant.suspend',
                'tenant_id' => $id,
                'error_type' => 'business',
            ]);

            session()->flash(
                'error',
                $exception->getMessage()
            );

            $this->redirectRoute(
                'central.tenants.index',
                ['status' => '']
            );
        } catch (Throwable $exception) {
            $errorLogger->report($exception, [
                'operation' => 'tenant.suspend',
                'tenant_id' => $id,
                'error_type' => 'unexpected',
            ]);

            session()->flash(
                'error',
                'Ocurrió un error inesperado al suspender el tenant.'
            );

            $this->redirectRoute(
                'central.tenants.index',
                ['status' => '']
            );
        }
    }

    #[\Livewire\Attributes\On('tenant-restore-confirmed')]
    public function restore(
        TenantLifecycleService $tenantLifecycle,
        ErrorLogger $errorLogger,
        string $id
    ): void {
        try {
            $tenantLifecycle->restore($id);

            session()->flash(
                'success',
                "El Tenant '{$id}' fue restaurado correctamente."
            );

            $this->redirectRoute(
                'central.tenants.index',
                ['status' => 'active']
            );
        } catch (DomainException $exception) {
            $errorLogger->report($exception, [
                'operation' => 'tenant.restore',
                'tenant_id' => $id,
                'error_type' => 'business',
            ]);

            session()->flash(
                'error',
                $exception->getMessage()
            );

            $this->redirectRoute(
                'central.tenants.index',
                ['status' => '']
            );
        } catch (Throwable $exception) {
            $errorLogger->report($exception, [
                'operation' => 'tenant.restore',
                'tenant_id' => $id,
                'error_type' => 'unexpected',
            ]);

            session()->flash(
                'error',
                'Ocurrió un error inesperado al restaurar el tenant.'
            );

            $this->redirectRoute(
                'central.tenants.index',
                ['status' => '']
            );
        }
    }

    #[\Livewire\Attributes\On('tenant-destroy-confirmed')]
    public function destroy(
        TenantLifecycleService $tenantLifecycle,
        ErrorLogger $errorLogger,
        string $id
    ): void {
        try {
            $tenantLifecycle->destroy($id);

            session()->flash(
                'success',
                "El Tenant '{$id}' fue eliminado definitivamente."
            );

            $this->redirectRoute(
                'central.tenants.index',
                ['status' => 'active']
            );
        } catch (DomainException $exception) {
            $errorLogger->report($exception, [
                'operation' => 'tenant.destroy',
                'tenant_id' => $id,
                'error_type' => 'business',
            ]);

            session()->flash(
                'error',
                $exception->getMessage()
            );

            $this->redirectRoute(
                'central.tenants.index',
                ['status' => 'suspend']
            );
        } catch (Throwable $exception) {
            $errorLogger->report($exception, [
                'operation' => 'tenant.destroy',
                'tenant_id' => $id,
                'error_type' => 'unexpected',
            ]);

            session()->flash(
                'error',
                'Ocurrió un error inesperado al eliminar definitivamente el tenant.'
            );

            $this->redirectRoute(
                'central.tenants.index',
                ['status' => 'suspend']
            );
        }
    }

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
                ->icon('default-restore')
                ->class('px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center justify-center')
                ->dispatch(
                    'open-confirm-modal',
                    [
                        'id' => $row->id,
                        'name' => $row->name,
                        'confirmEvent' => 'tenant-restore-confirmed',
                        'title' => 'Restaurar Inquilino',
                        'message' => '¿Está seguro de que desea restaurar este inquilino?',
                        'warning' => '',
                    ]
                );

            $actions[] = Button::add('destroy')
                ->icon('default-trash')
                ->class('px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 flex items-center justify-center')
                ->dispatch(
                    'open-confirm-modal',
                    [
                        'id' => $row->id,
                        'name' => $row->name,
                        'confirmEvent' => 'tenant-destroy-confirmed',
                        'title' => 'Eliminar Inquilino',
                        'message' => '¿Está seguro de que desea eliminar definitivamente este inquilino?',
                        'warning' => 'Esta accion no puede se revertir',
                    ]
                );
        } else {
            $actions[] = Button::add('edit')
                ->icon('default-pencil')
                ->class('px-2 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 flex items-center justify-center')
                ->route('central.tenants.edit', ['tenant' => $row->id]);

            $actions[] = Button::add('suspend')
                ->icon('default-suspend')
                ->class('px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 flex items-center justify-center')            
                ->dispatch(
                    'open-confirm-modal',
                    [
                        'id' => $row->id,
                        'name' => $row->name,
                        'confirmEvent' => 'tenant-suspend-confirmed',
                        'title' => 'Suspender Inquilino',
                        'message' => '¿Está seguro de que desea suspender este inquilino?',
                        'warning' => '',
                    ]
                );
        }

        return $actions;
    }
}


   

