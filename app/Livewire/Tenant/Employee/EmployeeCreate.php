<?php

declare(strict_types=1);

namespace App\Livewire\Tenant\Employee;

use App\Events\Tenant\UserCreated;
use App\Models\Tenant\User;
use App\Services\Central\ErrorLog\ErrorLogger;
use App\Services\Tenant\EmployeeUserCreator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Throwable;

class EmployeeCreate extends Component
{
    public string $rut = '';

    public string $nombres = '';

    public string $apellido_paterno = '';

    public string $apellido_materno = '';

    public string $movil = '';

    public string $email = '';

    public bool $is_active = true;

    public array $roleIds = [];

    protected function rules(): array
    {
        return [
            'rut' => [
                'nullable',
                'string',
                'max:10',
            ],

            'nombres' => [
                'required',
                'string',
                'max:255',
            ],

            'apellido_paterno' => [
                'nullable',
                'string',
                'max:255',
            ],

            'apellido_materno' => [
                'nullable',
                'string',
                'max:255',
            ],

            'movil' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class, 'email'),
            ],

            'is_active' => [
                'boolean',
            ],

            'roleIds' => [
                Rule::requiredIf($this->is_active),
                'array',
            ],

            'roleIds.*' => [
                'integer',
            ],
        ];
    }

    protected array $messages = [
        'nombres.required' => 'Debes ingresar los nombres.',
        'email.required' => 'Debes ingresar el correo electrónico.',
        'email.email' => 'El correo electrónico ingresado no es válido.',
        'email.unique' => 'El correo electrónico ya está registrado.',
        'roleIds.required' => 'Debes seleccionar al menos un rol.',
    ];

    public function updatedIsActive(bool $isActive): void
    {
        if (! $isActive) {
            $this->roleIds = [];
        }
    }

    public function removeRole(int $roleId): void
    {
        $this->roleIds = array_values(
            array_filter(
                $this->roleIds,
                fn ($id) => (int) $id !== $roleId
            )
        );
    }

    public function save(
        EmployeeUserCreator $employeeUserCreator,
        ErrorLogger $errorLogger,
    ) {
        $this->rut = trim($this->rut);
        $this->nombres = trim($this->nombres);
        $this->apellido_paterno = trim($this->apellido_paterno);
        $this->apellido_materno = trim($this->apellido_materno);
        $this->movil = trim($this->movil);
        $this->email = Str::lower(trim($this->email));

        $validated = $this->validate();

        $plainPassword = Str::random(8);

        try {
            $employee = $employeeUserCreator->create(
                employeeData: [
                    'rut' => $validated['rut'] ?: null,
                    'nombres' => $validated['nombres'],
                    'apellido_paterno' => $validated['apellido_paterno'] ?: null,
                    'apellido_materno' => $validated['apellido_materno'] ?: null,
                    'movil' => $validated['movil'] ?: null,
                    'state' => 'VIGENTE',
                ],
                email: $validated['email'],
                plainPassword: $plainPassword,
                isActive: $validated['is_active'],
                roleIds: $validated['roleIds'],
            );
        } catch (Throwable $exception) {
            session()->flash(
                'error',
                'Ocurrió un error al crear el empleado.'
            );

            return;
        }

        if ($employee->user->is_active) {
            try {
                event(
                    new UserCreated(
                        $employee->user,
                        $plainPassword
                    )
                );
            } catch (Throwable $exception) {
                $errorLogger->report($exception, [
                    'operation' => 'tenant.user.created.notification',
                    'tenant_id' => tenant()?->getTenantKey(),
                    'user_id' => $employee->user->id,
                ]);

                session()->flash(
                    'error',
                    'El empleado fue creado correctamente, pero no fue posible procesar la notificación.'
                );

                return redirect()->route('tenant.employees.index');
            }
        }

        session()->flash(
            'success',
            'Empleado creado correctamente.'
        );

        return redirect()->route('tenant.employees.index');
    }

    public function render()
    {
        $roles = Role::query()
            ->where('guard_name', 'tenant')
            ->where('name', '!=', 'inactive')
            ->orderBy('name')
            ->get(['id', 'name']);

        $selectedRoles = $roles
            ->filter(
                fn (Role $role) => in_array(
                    (int) $role->id,
                    array_map('intval', $this->roleIds),
                    true
                )
            )
            ->values();

        return view(
            'livewire.tenant.employee.employee-create',
            [
                'roles' => $roles,
                'selectedRoles' => $selectedRoles,
            ]
        );
    }
}