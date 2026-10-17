<?php

namespace App\Livewire\Central\User;

use App\Models\Central\User;
use App\Services\Central\ErrorLog\ErrorLogger;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Throwable;

class UserEdit extends Component
{
    #[Locked]
    public int $userId;

    public string $name = '';
    public string $email = '';
    public array $roleIds = [];
    public string $statusMessage = '';

    public function mount(): void
    {
        abort_if(tenant() !== null, 404);

        abort_unless(
            auth('web')->user()?->can('central.users.edit'),
            403
        );

        $user = request()->route('user');

        abort_unless($user instanceof User, 404);

        $this->userId = (int) $user->getKey();

        $this->assertCanManage($user);

        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';

        $this->roleIds = $user->roles()
            ->where('guard_name', 'web')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    private function assertCanManage(User $user): void
    {
        abort_if(
            $user->hasRole('SuperAdmin', 'web')
            && ! auth('web')->user()?->hasRole('SuperAdmin', 'web'),
            403
        );
    }

    private function editingUser(): User
    {
        abort_if(tenant() !== null, 404);

        abort_unless(
            auth('web')->user()?->can('central.users.edit'),
            403
        );

        $user = User::withTrashed()->findOrFail($this->userId);

        $this->assertCanManage($user);

        return $user;
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
                Rule::unique(User::class, 'email')
                    ->ignore($this->userId),
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
        'email.email' => 'El correo electrónico no es válido.',
        'email.max' => 'El correo electrónico no puede superar los 40 caracteres.',
        'email.unique' => 'El correo electrónico ya está registrado.',
        'roleIds.required' => 'Debes seleccionar al menos un rol.',
        'roleIds.min' => 'Debes seleccionar al menos un rol.',
        'roleIds.*.in' => 'Uno de los roles seleccionados no está permitido.',
        'roleIds.*.distinct' => 'No puedes seleccionar un rol más de una vez.',
    ];

    public function removeRole(int $roleId): void
    {
        $this->editingUser();

        $this->roleIds = array_values(array_filter(
            $this->roleIds,
            fn ($id) => (int) $id !== $roleId
        ));
    }

    public function save(ErrorLogger $errorLogger)
    {
        $this->editingUser();

        $this->name = trim($this->name);
        $this->email = Str::lower(trim($this->email));

        $validated = $this->validate();

        try {
            DB::transaction(function () use ($validated) {
                $user = User::withTrashed()
                    ->lockForUpdate()
                    ->findOrFail($this->userId);

                $this->assertCanManage($user);

                $roles = $this->assignableRoles()
                    ->whereIn('id', $validated['roleIds']);

                if ($roles->count() !== count($validated['roleIds'])) {
                    throw ValidationException::withMessages([
                        'roleIds' => 'Los roles seleccionados ya no están disponibles.',
                    ]);
                }

                if (
                    (int) auth('web')->id() === (int) $user->id
                    && $user->hasRole('SuperAdmin', 'web')
                    && ! $roles->contains('name', 'SuperAdmin')
                ) {
                    throw ValidationException::withMessages([
                        'roleIds' => 'No puedes quitarte tu propio rol SuperAdmin.',
                    ]);
                }

                $user->name = $validated['name'];
                $user->email = $validated['email'];
                $user->save();

                $user->syncRoles($roles);
            });

        } catch (ValidationException $exception) {
            throw $exception;

        } catch (Throwable $exception) {
            $errorLogger->report($exception, [
                'operation' => 'central.user.edit',
                'user_id' => $this->userId,
                'error_type' => 'unexpected',
            ]);

            $this->addError(
                'form',
                'Ocurrió un error al actualizar el usuario.'
            );

            return;
        }

        session()->flash(
            'success',
            'El usuario fue actualizado correctamente.'
        );

        return redirect()->route('central.users.index');
    }

    private function changeSuspension(
        bool $suspend,
        ErrorLogger $errorLogger
    ): void {
        $user = $this->editingUser();

        abort_unless(
            auth('web')->user()?->can('central.users.destroy'),
            403
        );

        if ($suspend) {
            if ((int) $user->id === (int) auth('web')->id()) {
                $this->addError(
                    'form',
                    'No puedes suspender tu propia cuenta.'
                );

                return;
            }

            if ($user->hasRole('SuperAdmin', 'web')) {
                $this->addError(
                    'form',
                    'No se permite suspender una cuenta SuperAdmin.'
                );

                return;
            }
        }

        try {
            if ($suspend && ! $user->trashed()) {
                $user->delete();
            }

            if (! $suspend && $user->trashed()) {
                $user->restore();
            }

            $this->resetErrorBag('form');

            $this->statusMessage = $suspend
                ? 'El usuario fue suspendido correctamente.'
                : 'El usuario fue reactivado correctamente.';

        } catch (Throwable $exception) {
            $errorLogger->report($exception, [
                'operation' => $suspend
                    ? 'central.user.suspend'
                    : 'central.user.reactivate',
                'user_id' => $this->userId,
                'error_type' => 'unexpected',
            ]);

            $this->addError(
                'form',
                'No fue posible modificar el estado del usuario.'
            );
        }
    }

    public function suspend(ErrorLogger $errorLogger): void
    {
        $this->changeSuspension(true, $errorLogger);
    }

    public function reactivate(ErrorLogger $errorLogger): void
    {
        $this->changeSuspension(false, $errorLogger);
    }

    public function render()
    {
        $user = $this->editingUser();

        $roles = $this->assignableRoles();
        $selectedIds = array_map('intval', $this->roleIds);

        return view('livewire.central.user.user-edit', [
            'user' => $user,
            'roles' => $roles,
            'selectedRoles' => $roles->filter(
                fn (Role $role) => in_array(
                    (int) $role->id,
                    $selectedIds,
                    true
                )
            ),
        ]);
    }
}
