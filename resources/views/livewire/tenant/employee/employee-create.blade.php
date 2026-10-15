<x-form wire:submit="save">

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 lg:gap-8">

        {{-- Columna izquierda: datos del empleado --}}
        <div
            class="space-y-6 rounded-xl border border-gray-200 p-6
                   dark:border-gray-700"
        >

            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Datos del empleado
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

            @if ($is_active && ! $withoutRoles)
                <div class="border-t border-gray-200 pt-6 dark:border-gray-700">

                    <div class="mb-4">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                            Roles asignados
                        </h3>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Roles seleccionados para el usuario.
                        </p>
                    </div>

                    @if ($selectedRoles->isEmpty())
                        <div
                            class="rounded-lg border border-dashed border-gray-300
                                   px-4 py-8 text-center text-sm text-gray-500
                                   dark:border-gray-700 dark:text-gray-400"
                        >
                            No hay roles seleccionados.
                        </div>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @foreach ($selectedRoles as $role)
                                <div
                                    wire:key="employee-selected-role-{{ $role->id }}"
                                    class="inline-flex items-center gap-2 rounded-lg
                                           border border-gray-200 bg-gray-50 px-3 py-2
                                           text-sm font-medium text-gray-700
                                           dark:border-gray-700 dark:bg-gray-800
                                           dark:text-gray-200"
                                >
                                    <span>
                                        {{ $role->name }}
                                    </span>

                                    <button
                                        type="button"