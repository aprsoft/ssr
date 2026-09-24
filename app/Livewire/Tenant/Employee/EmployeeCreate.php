<?php

namespace App\Livewire\Tenant\Employee;

use App\Events\Tenant\UserCreated;
use App\Models\Tenant\User;
use App\Services\ErrorLog\ErrorLogger;
use App\Services\Tenant\EmployeeUserCreator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Throwable;

class EmployeeCreate extends Component
{
    public string $nombres = '';
    public string $apellido_paterno = '';
    public string $apellido_materno = '';
    public string $email = '';

    protected function rules(): array
    {
        return [
            'nombres' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email:dns',
                'max:40',
                Rule::unique(User::class, 'email'),
            ],
        ];
    }

    protected array $messages = [
        'nombres.required' => 'Debes ingresar el nombre.',
        'nombres.max' => 'El nombre no puede superar los 255 caracteres.',
        'email.required' => 'Debes ingresar el correo electrónico.',
        'email.email' => 'El correo electrónico ingresado no es válido.',
        'email.max' => 'El correo electrónico no puede superar los 40 caracteres.',
        'email.unique' => 'El correo electrónico ingresado ya está registrado.',
    ];

    public function save(
        EmployeeUserCreator $employeeUserCreator,
        ErrorLogger $errorLogger,
    ) {
        try {
            $this->name = trim($this->name);
            $this->email = Str::lower(trim($this->email));

            $validated = $this->validate();

            $plainPassword = Str::random(6);

            $employee = $employeeUserCreator->create(
                employeeData: [
                    'name' => $validated['name'],
                    'state' => 'VIGENTE',
                ],
                email: $validated['email'],
                plainPassword: $plainPassword,
                isActive: true,
                emailVerified: true,
            );

            $user = $employee->user;
        } catch (ValidationException $exception) {
            $errorLogger->report($exception, [
                'operation' => 'tenant.user.create',
                'tenant_id' => tenant()?->getTenantKey(),
                'error_type' => 'validation',
                'validation_errors' => $exception->errors(),
            ]);

            throw $exception;
        } catch (QueryException $exception) {
            session()->flash(
                'error',
                'Ocurrió un error de base de datos al crear el usuario.'
            );

            return redirect()->route('tenant.users.create');
        } catch (Throwable $exception) {
            session()->flash(
                'error',
                'Ocurrió un error inesperado al crear el usuario.'
            );

            return redirect()->route('tenant.users.create');
        }

        try {
            event(
                new UserCreated(
                    $user,
                    $plainPassword
                )
            );
        } catch (Throwable $exception) {
            $errorLogger->report($exception, [
                'operation' => 'tenant.user.created.notification',
                'tenant_id' => tenant()?->getTenantKey(),
                'user_id' => $user->id,
                'error_type' => 'notification',
            ]);

            session()->flash(
                'error',
                sprintf(
                    'El usuario "%s" fue creado correctamente, pero ocurrió un error al procesar su notificación.',
                    $employee->name
                )
            );

            return redirect()->route('tenant.users.index');
        }

        session()->flash(
            'success',
            sprintf(
                'El usuario "%s" fue creado correctamente.',
                $employee->name
            )
        );

        return redirect()->route('tenant.users.index');
    }

    public function render()
    {
        return view(
            'livewire.tenant.employee.employee-create'
        );
    }
}