<?php

namespace App\Livewire\Central\Tenant;

use App\Services\Error\ErrorLogger;
use App\Services\Central\Tenant\CreateTenantService;
use DomainException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Throwable;

class CreateTenant extends Component
{
    public string $tenantId = '';

    public function save(
        CreateTenantService $createTenant,
        ErrorLogger $errorLogger
    ) {
        try {
            $this->tenantId = trim($this->tenantId);

            $validated = $this->validate([
                'tenantId' => [
                    'required',
                    'string',
                    'alpha_dash',
                    'max:20',
                ],
            ], [
                'tenantId.required' => 'Debes ingresar el ID del Tenant.',
                'tenantId.alpha_dash' => 'El ID solo puede contener letras, números, guiones y guiones bajos.',
                'tenantId.max' => 'El ID del Tenant no puede superar los 20 caracteres.',
            ]);

            $tenant = $createTenant->create(
                $validated['tenantId']
            );

            session()->flash(
                'success',
                sprintf(
                    'Tenant "%s" creado correctamente.',
                    $tenant->id
                )
            );

            return redirect()->route('central.tenants.index');
        } catch (ValidationException $exception) {
            /*
             * Política de SSR:
             * el error queda registrado en error_logs.
             *
             * Después relanzamos la excepción para que Livewire
             * conserve su error bag y muestre la validación.
             */
            $errorLogger->report(
                $exception,
                [
                    'operation' => 'tenant.create',
                    'tenant_id' => $this->tenantId ?: null,
                    'error_type' => 'validation',
                    'validation_errors' => $exception->errors(),
                ]
            );

            throw $exception;
        } catch (DomainException $exception) {
            $errorLogger->report(
                $exception,
                [
                    'operation' => 'tenant.create',
                    'tenant_id' => $this->tenantId ?: null,
                    'error_type' => 'business',
                ]
            );

            session()->flash(
                'error',
                $exception->getMessage()
            );

            return redirect()->route('central.tenants.create');
        } catch (QueryException $exception) {
            $errorLogger->report(
                $exception,
                [
                    'operation' => 'tenant.create',
                    'tenant_id' => $this->tenantId ?: null,
                    'error_type' => 'database',
                ]
            );

            session()->flash(
                'error',
                'Error de base de datos al crear la BD del tenant. Crear la BD de forma manual (verificar los prefijos por defecto de las nuevas BD, tenant_id y dominio), Tenancy montado en un servidor COMPARTIDO.'
            );

            return redirect()->route('central.tenants.create');
        } catch (Throwable $exception) {
            $errorLogger->report(
                $exception,
                [
                    'operation' => 'tenant.create',
                    'tenant_id' => $this->tenantId ?: null,
                    'error_type' => 'unexpected',
                ]
            );

            session()->flash(
                'error',
                'Ocurrió un error inesperado al crear el tenant.'
            );

            return redirect()->route('central.tenants.create');
        }
    }

    public function render()
    {
        return view(
            'livewire.central.tenant.create-tenant'
        );
    }
}