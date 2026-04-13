<x-form wire:submit="save">
    
    <x-input 
        label="Nombre" 
        wire:model.live="name" 
        :disabled="$saving"
    />

    <x-input 
        label="Email" 
        wire:model.live="email" 
        :disabled="$saving"
    />

    <x-slot:actions>
        <x-button 
            label="Guardar" 
            type="submit"          
            :disabled="$saving"
            spinner
        />
    </x-slot:actions>

</x-form>