<div class="space-y-6">

    <form wire:submit="update">

        <div
            class="rounded-2xl border border-gray-200 bg-white p-5
                   dark:border-gray-800 dark:bg-white/[0.03] sm:p-6"
        >
            {{-- Encabezado --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Editar Inquilino
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Modifica la información disponible del tenant.
                </p>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6">

                {{-- ID del Tenant --}}
                <div>
                    <label
                        for="tenantId"
                        class="mb-1.5 block text-sm font-medium
                               text-gray-700 dark:text-gray-400"
                    >
                        ID del Tenant
                    </label>

                    <input
                        type="text"
                        id="tenantId"
                        value="{{ $tenantId }}"
                        disabled
                        class="h-11 w-full rounded-lg border
                               border-gray-300 bg-gray-100 px-4 py-2.5
                               text-sm text-gray-700 shadow-theme-xs
                               outline-none
                               dark:border-gray-700 dark:bg-gray-800
                               dark:text-gray-400"
                    >

                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                        El identificador del tenant no se modifica desde este formulario.
                    </p>
                </div>

                {{-- Dominio --}}
                <div>
                    <label
                        for="domain"
                        class="mb-1.5 block text-sm font-medium
                               text-gray-700 dark:text-gray-400"
                    >
                        Dominio
                    </label>

                    <input
                        type="text"
                        id="domain"
                        wire:model="domain"
                        maxlength="255"
                        required
                        autocomplete="off"
                        wire:loading.attr="disabled"
                        wire:target="update"
                        class="h-11 w-full rounded-lg border
                               border-gray-300 bg-transparent px-4 py-2.5
                               text-sm text-gray-800 shadow-theme-xs
                               outline-none placeholder:text-gray-400
                               focus:border-brand-300
                               focus:ring-3 focus:ring-brand-500/10
                               disabled:cursor-not-allowed
                               disabled:opacity-60
                               dark:border-gray-700 dark:bg-gray-900
                               dark:text-white/90
                               dark:placeholder:text-white/30"
                    >

                    @error('domain')
                        <p class="mt-1.5 text-xs text-error-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- Acciones --}}
            <div
                class="mt-8 flex flex-col-reverse gap-3
                       sm:flex-row sm:justify-end"
            >
                <a
                    href="{{ route('central.tenants.show', $tenantId) }}"
                    wire:loading.class="pointer-events-none opacity-50"
                    wire:target="update"
                    class="inline-flex items-center justify-center
                           rounded-lg border border-gray-300 bg-white
                           px-4 py-2.5 text-sm font-medium text-gray-700
                           shadow-theme-xs hover:bg-gray-50
                           dark:border-gray-700 dark:bg-gray-800
                           dark:text-gray-400
                           dark:hover:bg-white/[0.03]"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="update"
                    class="inline-flex min-w-40 items-center justify-center
                           rounded-lg bg-brand-500 px-4 py-2.5
                           text-sm font-medium text-white shadow-theme-xs
                           transition hover:bg-brand-600
                           disabled:cursor-not-allowed
                           disabled:opacity-60"
                >
                    <span
                        wire:loading.remove
                        wire:target="update"
                    >
                        Guardar cambios
                    </span>

                    <span
                        wire:loading.inline-flex
                        wire:target="update"
                        class="items-center gap-2"
                    >
                        <svg
                            class="h-4 w-4 animate-spin"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>

                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                            ></path>
                        </svg>

                        Actualizando...
                    </span>
                </button>
            </div>

        </div>

    </form>

</div>