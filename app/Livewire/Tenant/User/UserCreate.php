<?php

namespace App\Livewire\Tenant\User;

use App\Events\Tenant\UserCreated;
use App\Models\Tenant\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

class UserCreate extends Component
{
    use Toast;

    public string $name   = '';  
    public string $email  = '';
    public bool   $saving = false;

    protected function rules(): array
    {
        return [
            'name'  => ['required'],
            'email' => ['required', 'email:dns', 'max:40', 'unique:users,email'],
            // 'email' => ['required', 'email:dns', 'max:40'],
        ];
    }

    protected array $messages = [
        'email.unique' => 'El correo electronico ingresado ya está registrado.',
    ];

    // En el componente Livewire
    protected $listeners = ['echo:user-channel,.user-email-sent' => 'onEmailSent'];

    public function onEmailSent($data): void
    {
        $this->saving = false;
        $this->success($data['message']); // toast de MaryUI
        $this->reset();
    }

    public function updatedName(string $value): void
    {
        $this->name = strtoupper($value);
    }

    public function updatedEmail(string $value): void
    {
        $this->email = strtolower($value);
    }
        
    public function save()
    {
        $this->validate();
        $this->saving = true;

        $plainPassword = Str::random(6);

        try {
            $user = User::create([
                    'name'               => $this->name,
                    'email'              => $this->email,
                    //  'movil'              => $this->movil,
                    'email_verified_at'  => now(),
                    'password'           => Hash::make($plainPassword),
                    'remember_token'     => Str::random(6),
                ]);

            event(new UserCreated($user, $plainPassword));

            $this->toast(
                type: 'success',
                title: 'Usuario creado',
                description: 'El usuario se ha registrado correctamente',
                timeout: 5000
            );

            //$this->redirectRoute('tenant.users.index');

        } catch (QueryException $e) {

            Log::error('Error SQL al crear usuario', [
                'exception' => $e,
                'sql'       => $e->getSql(),
                'bindings'  => $e->getBindings(),
                'tenant'    => tenant('id') ?? 'central',
            ]);

            $this->error($e->getMessage(),timeout: 5000,);


        } catch (Throwable $e) {           

            $this->error($e->getMessage(),timeout: 5000,);
        }

       
    }

    public function render()
    {
        return view('livewire.tenant.user.user-create');
    }
}