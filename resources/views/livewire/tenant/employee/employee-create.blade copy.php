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

            @if ($is_active)
                <div class="border-t border-gray-200 pt-6 dark:border-gray-700">

                    <div class="mb-4">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                            Roles asignados
                        </h3>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Roles que tendrá actualmente el usuario.
                        </p>
                    </div>

                    @if ($selectedRoles->isEmpty())
                        <div
                            class="rounded-lg border border-dashed border-gray-300
                                   px-4 py-8 text-center text-sm text-gray-500
                                   dark:border-gray-700 dark:text-gray-400"
                        >
                            No hay roles asignados.
                        </div>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @foreach ($selectedRoles as $role)
                                <div
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
                                        wire:click="removeRole({{ $role->id }})"
                                        class="text-gray-400 transition hover:text-red-500"
                                        aria-label="Quitar rol {{ $role->name }}"
                                    >
                                        &times;
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            @endif

        </div>

        {{-- Columna derecha: configuración del usuario --}}
        <div
            class="space-y-6 rounded-xl border border-gray-200 p-6
                   dark:border-gray-700"
        >

            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Estado del usuario
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Define si el empleado tendrá acceso al sistema.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                <label
                    class="flex cursor-pointer items-center gap-3 rounded-lg
                           border p-4 transition
                           {{ $is_active
                               ? 'border-gray-900 bg-gray-50 dark:border-gray-400 dark:bg-gray-800'
                               : 'border-gray-200 dark:border-gray-700' }}"
                >
                    <input
                        type="radio"
                        wire:model.live="is_active"
                        value="1"
                        class="h-4 w-4"
                    >

                    <div>
                        <div class="font-medium text-gray-900 dark:text-white">
                            Activo
                        </div>

                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            Puede acceder al sistema.
                        </div>
                    </div>
                </label>

                <label
                    class="flex cursor-pointer items-center gap-3 rounded-lg
                           border p-4 transition
                           {{ ! $is_active
                               ? 'border-gray-900 bg-gray-50 dark:border-gray-400 dark:bg-gray-800'
                               : 'border-gray-200 dark:border-gray-700' }}"
                >
                    <input
                        type="radio"
                        wire:model.live="is_active"
                        value="0"
                        class="h-4 w-4"
                    >

                    <div>
                        <div class="font-medium text-gray-900 dark:text-white">
                            Inactivo
                        </div>

                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            Sin acceso al sistema.
                        </div>
                    </div>
                </label>

            </div>

            @if ($is_active)

                <div class="border-t border-gray-200 pt-6 dark:border-gray-700">

                    <div class="mb-4">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                            Roles disponibles
                        </h3>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Selecciona uno o más roles para el usuario.
                        </p>
                    </div>

                    <div
                        class="max-h-80 space-y-2 overflow-y-auto rounded-lg
                               border border-gray-200 p-3
                               dark:border-gray-700"
                    >
                        @forelse ($roles as $role)

                            <label
                                class="flex cursor-pointer items-center gap-3
                                       rounded-lg px-3 py-2 transition
                                       hover:bg-gray-50 dark:hover:bg-gray-800"
                            >
                                <input
                                    type="checkbox"
                                    value="{{ $role->id }}"
                                    wire:model.live="roleIds"
                                    class="h-4 w-4 rounded"
                                >

                                <span
                                    class="text-sm font-medium text-gray-700
                                           dark:text-gray-200"
                                >
                                    {{ $role->name }}
                                </span>
                            </label>

                        @empty

                            <div
                                class="py-6 text-center text-sm text-gray-500
                                       dark:text-gray-400"
                            >
                                No existen roles disponibles.
                            </div>

                        @endforelse
                    </div>

                    @error('roleIds')
                        <p class="mt-2 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            @endif

        </div>

    </div>

    <x-slot:actions>
        <x-button
            label="Guardar"
            type="submit"
        />
    </x-slot:actions>

</x-form>