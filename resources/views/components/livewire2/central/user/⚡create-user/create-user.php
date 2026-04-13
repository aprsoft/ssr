<?php

use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast; 
    
    public function save()
    {
        // Validación y guardado...
        
        $this->success(
            title: 'Usuario creado',
            description: "El usuario  se ha registrado correctamente",
            timeout: 5000
        );
        
    }
};