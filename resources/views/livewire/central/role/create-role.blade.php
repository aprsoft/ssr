<div class="space-y-6">

    <form wire:submit="save" class="space-y-6">

        {{-- Datos principales --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm
                   dark:border-gray-800 dark:bg-white/[0.03]"
        >
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- Nombre del rol --}}
                <div>
                    <div class="mb-4">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                            Nuevo rol
                        </h3>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Ingresa el nombre que identificará al rol.
                        </p>
                    </div>

                    <x-input
                        label="Nombre del rol"
                        wire:model="role"
                        placeholder="Ej: Supervisor"
                    />

                    @error('role')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Permisos seleccionados --}}
                <div>
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                                Permisos seleccionados
                            </h3>

                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Los permisos marcados en la tabla aparecerán aquí.
                            </p>
                        </div>

                        <span
                            class="inline-flex min-w-8 items-center justify-center rounded-full
                                   bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700
                                   dark:bg-gray-800 dark:text-gray-300"
                        >
                            {{ count($selectedPermissions) }}
                        </span>
                    </div>

                    <div
                        class="min-h-32 rounded-xl border border-dashed border-gray-300
                               bg-gray-50 p-4
                               dark:border-gray-700 dark:bg-gray-900/40"
                    >
                        @if (count($selectedPermissions))
                            <div class="flex flex-wrap gap-2">
                                @foreach ($selectedPermissions as $permission)
                                    <span
                                        wire:key="selected-permission-{{ $permission['id'] }}"
                                        class="inline-flex items-center rounded-lg
                                               bg-blue-50 px-3 py-1.5
                                               text-sm font-medium text-blue-700
                                               ring-1 ring-inset ring-blue-700/10
                                               dark:bg-blue-500/10 dark:text-blue-400"
                                    >
                                        {{ $permission['name'] }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <div
                                class="flex min-h-24 items-center justify-center
                                       text-center text-sm text-gray-500
                                       dark:text-gray-400"
                            >
                                Aún no has seleccionado permisos.
                            </div>
                        @endif
                    </div>

                    @error('permissionIds')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>
        </div>


        {{-- PowerGrid --}}
        <div
            x-data
            x-on:click="
                setTimeout(() => {
                    const ids = window.pgBulkActions?.get('permissionTable') ?? [];

                    $wire.updateSelectedPermissions(ids);
                }, 50);
            "
        >
            <livewire:central.permission.permission-table />
        </div>


        {{-- Acciones --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

            <a
                href="{{ route('central.roles.index') }}"
                class="inline-flex items-center justify-center rounded-lg
                       border border-gray-300 bg-white px-4 py-2.5
                       text-sm font-medium text-gray-700 shadow-sm
                       transition hover:bg-gray-50
                       dark:border-gray-700 dark:bg-gray-800
                       dark:text-gray-300 dark:hover:bg-gray-700"
            >
                Cancelar
            </a>

            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="save"
                class="inline-flex items-center justify-center rounded-lg
                       bg-blue-600 px-4 py-2.5
                       text-sm font-medium text-white shadow-sm
                       transition hover:bg-blue-700
                       disabled:cursor-not-allowed disabled:opacity-60"
            >
                <span wire:loading.remove wire:target="save">
                    Guardar rol
                </span>

                <span wire:loading wire:target="save">
                    Guardando...
                </span>
            </button>

        </div>

    </form>

</div>