<x-form wire:submit="save">

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

        <x-input
            label="RUT"
            wire:model.live="rut"
            maxlength="10"
        />

        <x-input
            label="Nombres"
            wire:model.live="nombres"
            required
        />

        <x-input
            label="Apellido paterno"
            wire:model.live="apellido_paterno"
        />

        <x-input
            label="Apellido materno"
            wire:model.live="apellido_materno"
        />

        <x-input
            label="Móvil"
            wire:model.live="movil"
        />

        <x-input
            label="Email"
            type="email"
            wire:model.live="email"
            required
        />

    </div>

    <div class="mt-6">

        <div class="mb-2 font-medium">
            Roles
        </div>

        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($roles as $role)
                <x-checkbox
                    label="{{ $role->name }}"
                    value="{{ $role->id }}"
                    wire:model="roleIds"
                />
            @endforeach
        </div>

        @error('roleIds')
            <div class="mt-2 text-sm text-red-500">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="mt-6">
        <x-toggle
            label="Usuario activo"
            wire:model.live="is_active"
        />
    </div>

    <x-slot:actions>
        <x-button
            label="Guardar"
            type="submit"
            spinner="save"
        />
    </x-slot:actions>

</x-form>