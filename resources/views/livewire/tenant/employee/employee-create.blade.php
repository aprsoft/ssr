<x-form wire:submit="save">
    
    <x-input 
        label="Nombre" 
        wire:model.live="nombres" 
        
    />    

    <x-input 
        label="Email" 
        wire:model.live="email" 
       
    />

    <x-slot:actions>
        <x-button 
            label="Guardar" 
            type="submit"          
            
        />
    </x-slot:actions>

</x-form>