<?php

namespace App\Livewire\Central\User;

use App\Events\Central\UserCreated;
use App\Models\Central\User;
use App\Services\Central\ErrorLog\ErrorLogger;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Livewire\Component;
use Throwable;

class UserCreate extends Component
{
    public string $name = '';
    public string $email = '';

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email:dns',
                'max:40',
                // 'unique:users,email',
                // 'unique:users',
            ],
        ];
    }

    protected array $messages = [
        'name.required' => 'Debes ingresar el nombre del usuario.',
        'name.max' => 'El nombre no puede superar los 255 caracteres.',
        'email.required' => 'Debes ingresar el correo electrónico.',
        'email.email' => 'El correo electrónico ingresado no es válido.',
        'email.max' => 'El correo electrónico no puede superar los 40 caracteres.',
        'email.unique' => 'El correo electrónico ingresado ya está registrado.',
    ];

    public function save(ErrorLogger $errorLogger) {
        try {
            $this->name = trim($this->name);
            $this->email = Str::lower(trim($this->email));

            $validated = $this->validate();

            $plainPassword = Str::random(6);

            $user = new User();

            $user->name = $validated['name'];
            $user->email = $validated['email'];

            /*
             * PROPUESTO:
             * La columna state es obligatoria y la vista actual
             * no permite elegir estado.
             *
             * Un usuario nuevo se crea activo.
             */

            // $user->state = 'VIGENTE';

            $user->email_verified_at = now();

            /*
             * App\Models\Central\User tiene cast:
             *
             * 'password' => 'hashed'
             *
             * por lo que Eloquent realizará el hash.
             */
            $user->password = $plainPassword;

            $user->remember_token = Str::random(60);

            $user->save();
        } catch (ValidationException $exception) {
            $errorLogger->report(
                $exception,
                [
                    'operation' => 'central.user.create',
                    'error_type' => 'validation',
                    'validation_errors' => $exception->errors(),
                ]
            );

            $message = collect($exception->errors())
                ->flatten()
                ->first();

            session()->flash(
                'error',
                $message ?? 'Error de validación al crear el usuario.'
            );

            return redirect()->route('central.users.create');
        } catch (QueryException $exception) {
            $errorLogger->report(
                $exception,
                [
                    'operation' => 'central.user.create',
                    'error_type' => 'database',
                ]
            );

            session()->flash(
                'error',
                'Ocurrió un error de base de datos al crear el usuario.'
            );

            return redirect()->route('central.users.create');
        } catch (Throwable $exception) {
            $errorLogger->report(
                $exception,
                [
                    'operation' => 'central.user.create',
                    'error_type' => 'unexpected',
                ]
            );

            session()->flash(
                'error',
                'Ocurrió un error inesperado al crear el usuario.'
            );

            return redirect()->route('central.users.create');
        }

        /*
         * La creación del usuario ya terminó correctamente.
         *
         * La notificación se maneja aparte para que un fallo en
         * correo/broadcast no haga creer que el usuario no se creó.
         */
        try {
            event(
                new UserCreated(
                    $user,
                    $plainPassword
                )
            );
        } catch (Throwable $exception) {
            $errorLogger->report(
                $exception,
                [
                    'operation' => 'central.user.created.notification',
                    'user_id' => $user->id,
                    'error_type' => 'notification',
                ]
            );

            session()->flash(
                'error',
                sprintf(
                    'El usuario "%s" fue creado correctamente, pero ocurrió un error al procesar su notificación.',
                    $user->name
                )
            );

            return redirect()->route('central.users.index');
        }

        session()->flash(
            'success',
            sprintf(
                'El usuario "%s" fue creado correctamente.',
                $user->name
            )
        );

        return redirect()->route('central.users.index');
    }

    public function render()
    {
        return view(
            'livewire.central.user.user-create'
        );
    }
}