<?php

declare(strict_types=1);

namespace App\Livewire\Central\User;

use App\Models\Central\User;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UserShow extends Component
{
    #[Locked]
    public int $userId;

   
    public function mount(): void
    {
        // Garantizar contexto central.
        abort_if(tenant() !== null, 404);

        // Verificar autorización.
        abort_unless(
            auth('web')->user()?->can('central.users.index'),
            403
        );

        // Obtener el usuario desde la ruta.
        $user = request()->route('user');

        // Verificar que sea un usuario central válido.
        abort_unless($user instanceof User, 404);

        // Proteger al usuario principal (ID 1).
        abort_if((int) $user->getKey() === 1, 404);

        // Guardar identificador protegido por Livewire.
        $this->userId = (int) $user->getKey();
    }


    public function render()
    {
        abort_if($this->userId === 1, 404);
        abort_if(tenant() !== null, 404);
        abort_unless(auth('web')->user()?->can('central.users.index'), 403);

        $user = User::query()
            ->with([
                'roles' => fn ($query) => $query
                    ->where('guard_name', 'web')
                    ->orderBy('name'),
            ])
            ->findOrFail($this->userId);

        return view('livewire.central.user.user-show', [
            'user' => $user,
        ]);
    }
}
