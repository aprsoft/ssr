<?php

namespace App\Livewire\Central\Tenant;

use App\Models\Central\Tenant;
use App\Services\Error\ErrorLogger;
use App\Services\Tenant\UpdateTenantService;
use DomainException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Exceptions\DomainOccupiedByOtherTenantException;
use Throwable;

class EditTenant extends Component
{
    public string $tenantId = '';

    public ?int $domainId = null;

    public string $domain = '';

    public function mount(string $tenantId): void
    {
        $tenant = Tenant::query()
            ->with('domains')
            ->findOrFail($tenantId);

        $tenantDomain = $tenant->domains->first();

        $this->tenantId = $tenant->id;
        $this->domainId = $tenantDomain?->id;
        $this->domain = $tenantDomain?->domain ?? '';
    }

    public function update(
        UpdateTenantService $updateTenant,
        ErrorLogger $errorLogger
    ) {
        try {
            $this->domain = trim($this->domain);

            /*
             * La tabla domains tiene UNIQUE(domain).
             *
             * Al editar ignoramos el dominio actualmente
             * perteneciente al tenant.
             */
            $uniqueDomain = Rule::unique(
                Domain::class,
                'domain'
            );

            if ($this->domainId !== null) {
                $uniqueDomain->ignore($this->domainId);
            }

            $validated = $this->validate([
                'domain' => [
                    'required',
                    'string',
                    'max:255',
                    $uniqueDomain,
                ],
            ], [
                'domain.required' => 'Debes ingresar el dominio.',
                'domain.unique' => 'El dominio ya está asignado a otro tenant.',
                'domain.max' => 'El dominio no puede superar los 255 caracteres.',
            ]);

            $domain = $updateTenant->updateDomain(
                $this->tenantId,
                $validated['domain']
            );

            session()->flash(
                'success',
                sprintf(
                    'El Tenant "%s" fue actualizado correctamente. Dominio: %s',
                    $this->tenantId,
                    $domain
                )
            );

            return redirect()->route(
                'central.tenants.show',
                [
                    'tenant' => $this->tenantId,
                ]
            );
        } catch (ValidationException $exception) {
            $errorLogger->report(
                $exception,
                [
                    'operation' => 'tenant.update',
                    'tenant_id' => $this->tenantId,
                    'error_type' => 'validation',
                    'validation_errors' => $exception->errors(),
                ]
            );

            /*
             * Relanzamos para conservar el ErrorBag
             * normal de Livewire.
             */
            throw $exception;
        } catch (DomainOccupiedByOtherTenantException $exception) {
            $errorLogger->report(
                $exception,
                [
                    'operation' => 'tenant.update',
                    'tenant_id' => $this->tenantId,
                    'error_type' => 'business',
                ]
            );

            session()->flash(
                'error',
                'El dominio indicado ya está asignado a otro tenant.'
            );

            return redirect()->route(
                'central.tenants.edit',
                [
                    'tenant' => $this->tenantId,
                ]
            );
        } catch (DomainException $exception) {
            $errorLogger->report(
                $exception,
                [
                    'operation' => 'tenant.update',
                    'tenant_id' => $this->tenantId,
                    'error_type' => 'business',
                ]
            );

            session()->flash(
                'error',
                $exception->getMessage()
            );

            return redirect()->route(
                'central.tenants.edit',
                [
                    'tenant' => $this->tenantId,
                ]
            );
        } catch (QueryException $exception) {
            $errorLogger->report(
                $exception,
                [
                    'operation' => 'tenant.update',
                    'tenant_id' => $this->tenantId,
                    'error_type' => 'database',
                ]
            );

            session()->flash(
                'error',
                'Ocurrió un error de base de datos al actualizar el tenant.'
            );

            return redirect()->route(
                'central.tenants.edit',
                [
                    'tenant' => $this->tenantId,
                ]
            );
        } catch (Throwable $exception) {
            $errorLogger->report(
                $exception,
                [
                    'operation' => 'tenant.update',
                    'tenant_id' => $this->tenantId,
                    'error_type' => 'unexpected',
                ]
            );

            session()->flash(
                'error',
                'Ocurrió un error inesperado al actualizar el tenant.'
            );

            return redirect()->route(
                'central.tenants.edit',
                [
                    'tenant' => $this->tenantId,
                ]
            );
        }
    }

    public function render()
    {
        return view(
            'livewire.central.tenant.edit-tenant'
        );
    }
}