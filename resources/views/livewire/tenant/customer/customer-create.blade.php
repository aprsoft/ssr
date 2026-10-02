<x-form wire:submit="save">

    <div>
        <x-input
            label="RUT"
            wire:model="customer.rut"
        />
        <x-input
            label="Nombres"
            wire:model="customer.nombres"
        />
        <x-input
            label="Apellido Paterno"
            wire:model="customer.apellido_paterno"
        />
        <x-input
            label="Apellido Materno"
            wire:model="customer.apellido_materno"
        />
        <x-input
            label="Email"
            wire:model="customer.email"
        />
        <x-input
            label="Móvil"
            wire:model="customer.movil"
        />       
    </div>

    <x-slot:actions>
        <x-button
            label="Guardar"
            type="submit"
        />
    </x-slot:actions>

</x-form>