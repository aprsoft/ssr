<?php

namespace App\Livewire;

use Livewire\Component;
use Mary\Traits\Toast;

class UserCreate extends Component
{
    use Toast;
    
    public function save()
    {
        $this->success(
            title: 'Usuario creado',
            description: "El usuario se ha registrado correctamente",
            timeout: 5000
        );        
    }
}