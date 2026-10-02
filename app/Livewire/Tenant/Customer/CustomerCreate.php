<?php

declare(strict_types=1);

namespace App\Livewire\Tenant\Customer;

use App\Models\Tenant\Customer;
use App\Services\Central\ErrorLog\ErrorLogger;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Throwable;

class CustomerCreate extends Component
{
    public string $rut = '';

    public string $nombres = '';

    public string $apellido_paterno = '';

    public string $apellido_materno = '';

    public string $movil = '';

    public string $email = '';



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

        ];
    }

    protected array $messages = [
        'nombres.required' => 'Debes ingresar los nombres.',
        'email.required' => 'Debes ingresar el correo electrónico.',
        'email.email' => 'El correo electrónico ingresado no es válido.',
        'email.unique' => 'El correo electrónico ya está registrado.',
    ];





    public function save(
   
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
            
            );
        } catch (Throwable $exception) {
            session()->flash(
                'error',
                'Ocurrió un error al crear el empleado, contacte al administrador del sistema.'
            );

            return redirect()->route('tenant.customers.index');
        }

       

        session()->flash(
            'success',
            'Empleado creado correctamente.'
        );

        return redirect()->route('tenant.customers.index');
    }

    public function render()
    {
        return view('livewire.tenant.customer.customer-create');
    }
}