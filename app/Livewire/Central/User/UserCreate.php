<?php

namespace App\Livewire\Central\User;

use App\Events\Central\UserCreated;
use App\Models\Central\User;
use App\Services\Central\ErrorLog\ErrorLogger;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Throwable;

class UserCreate extends Component
{
    public string $name = '';
    public string $email = '';
    public array $roleIds = [];

    public function mount(): void
    {
        abort_unless(auth('web')->user()?->can('central.users.create'), 403);
    }

    private function assignableRoles(): Collection
    {
        return Role::query()
            ->where('guard_name', 'web')
            ->when(
                ! auth('web')->user()?->hasRole('SuperAdmin', 'web'),
                fn ($query) => $query->where('name', '!=', 'SuperAdmin')
            )
            ->orderBy('name')
            ->get(['id', 'name', 'guard_name']);
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email:dns',
                'max:40',
                // Rule::unique(User::class, 'email'),
            ],
            'roleIds' => ['required', 'array', 'min:1'],
            'roleIds.*' => [
                'integer',
                'distinct',
                Rule::in($this->assignableRoles()->modelKeys()),
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
        'roleIds.required' => 'Debes seleccionar al menos un rol.',
        'roleIds.min' => 'Debes seleccionar al menos un rol.',
        'roleIds.*.in' => 'Uno de los roles seleccionados no está permitido.',
        'roleIds.*.distinct' => 'No puedes seleccionar un rol más de una vez.',
    ];

    public function removeRole(int $roleId): void
    {
        $this->roleIds = array_values(array_filter(
            $this->roleIds,
            fn ($id) => (int) $id !== $roleId
        ));
    }

    public function save(ErrorLogger $errorLogger)
    {
        abort_unless(auth('web')->user()?->can('central.users.create'), 403);

        try {
            $this->name = trim($this->name);
            $this->email = Str::lower(trim($this->email));

            $validated = $this->validate();
            $plainPassword = Str::random(6);

            $user = DB::transaction(function () use ($validated, $plainPassword) {
                $user = new User();
                $user->name = $validated['name'];
                $user->email = $validated['email'];
                $user->email_verified_at = now();
                $user->password = $plainPassword;
                $user->remember_token = Str::random(60);
                $user->save();

                $roles = $this->assignableRoles()
                    ->whereIn('id', $validated['roleIds']);

                if ($roles->count() !== count($validated['roleIds'])) {
                    throw ValidationException::withMessages([
                        'roleIds' => 'Los roles seleccionados ya no están disponibles.',
                    ]);
                }

                $user->syncRoles($roles);

                return $user;
            });
        } catch (ValidationException $exception) {
            $errorLogger->report($exception, [
                'operation' => 'central.user.create',
                'error_type' => 'validation',
                'validation_errors' => $exception->errors(),
            ]);

            session()->flash(
                'error',
                collect($exception->errors())->flatten()->first()
                    ?? 'Error de validación al crear el usuario.'
            );

            return redirect()->route('central.users.create');
        } catch (QueryException $exception) {
            $errorLogger->report($exception, [
                'operation' => 'central.user.create',
                'error_type' => 'database',
            ]);

            session()->flash(
                'error',
                'Ocurrió un error de base de datos al crear el usuario.'
            );

            return redirect()->route('central.users.create');
        } catch (Throwable $exception) {
            $errorLogger->report($exception, [
                'operation' => 'central.user.create',
                'error_type' => 'unexpected',
            ]);

            session()->flash(
                'error',
                'Ocurrió un error inesperado al crear el usuario.'
            );

            return redirect()->route('central.users.create');
        }

        try {

            event(new UserCreated($user, $plainPassword));

        } catch (Throwable $exception) {
            $errorLogger->report($exception, [
                'operation' => 'central.user.created.notification',
                'user_id' => $user->id,
                'error_type' => 'notification',
            ]);

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
            sprintf('El usuario "%s" fue creado correctamente.', $user->name)
        );

        return redirect()->route('central.users.index');
    }

    public function render()
    {
        $roles = $this->assignableRoles();
        $selectedIds = array_map('intval', $this->roleIds);

        return view('livewire.central.user.user-create', [
            'roles' => $roles,
            'selectedRoles' => $roles->filter(
                fn (Role $role) => in_array((int) $role->id, $selectedIds, true)
            ),
        ]);
    }
}
