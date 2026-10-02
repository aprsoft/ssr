<x-form wire:submit="save">

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 lg:gap-8">

        {{-- Columna izquierda: datos del empleado --}}
        <div
            class="space-y-6 rounded-xl border border-gray-200 p-6
                   dark:border-gray-700"
        >

            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Datos del Cliente
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Información personal y de contacto.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

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

       
        </div>


    </div>

    <x-slot:actions>
        <x-button
            label="Guardar"
            type="submit"
        />
    </x-slot:actions>

</x-form>