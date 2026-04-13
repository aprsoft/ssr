<x-form wire:submit="save">
    
    <x-input label="Nombre" wire:model="name" placeholder="Nombre completo" icon="o-user" hint="" />
    <x-input label="email" wire:model="email" placeholder="usuario@aprsoft.cl" icon="o-envelope" hint="" />
    <x-slot:actions>
            <x-button label="Guardar" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>

  
</x-form>