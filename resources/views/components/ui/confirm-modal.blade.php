@props([
    'openEvent',
    'confirmEvent',
    'title' => 'Confirmar acción',
    'message' => '¿Está seguro de continuar?',
    'warning' => null,
    'confirmText' => 'Confirmar',
    'cancelText' => 'Cancelar',
])

<div
    x-data="{
        payload: {
            id: null,
            name: '',
        },

        confirmEvent: @js($confirmEvent),
    }"
    x-on:{{ $openEvent }}.window="
        payload = {
            id: $event.detail.id ?? null,
            name: $event.detail.name ?? '',
        }
    "
>
    <x-ui.modal
        :isOpen="false"
        :showCloseButton="false"
        :openEvent="$openEvent"
        class="max-w-md"
        role="dialog"
        aria-modal="true"
        aria-label="{{ $title }}"
    >
        <div class="p-6 text-center sm:p-8">

            {{-- Icono --}}
            <div
                class="mx-auto flex h-14 w-14 items-center justify-center
                       rounded-full bg-red-50 text-red-600
                       dark:bg-red-500/10 dark:text-red-400"
            >
                <svg
                    class="h-7 w-7"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v4m0 4h.01M10.29 3.86 1.82 18
                           a2 2 0 0 0 1.71 3h16.94
                           a2 2 0 0 0 1.71-3L13.71 3.86
                           a2 2 0 0 0-3.42 0Z"
                    />
                </svg>
            </div>

            {{-- Título --}}
            <h3
                class="mt-5 text-xl font-semibold
                       text-gray-900 dark:text-white"
            >
                {{ $title }}
            </h3>

            {{-- Mensaje --}}
            <p
                class="mt-2 text-sm leading-6
                       text-gray-500 dark:text-gray-400"
            >
                {{ $message }}
            </p>

            {{-- Recurso --}}
            <div
                x-show="payload.name"
                x-cloak
                class="mt-4 rounded-xl border border-gray-200
                       bg-gray-50 px-4 py-3
                       dark:border-gray-800 dark:bg-gray-800/50"
            >
                <span
                    class="break-all text-sm font-semibold
                           text-gray-800 dark:text-gray-200"
                    x-text="payload.name"
                ></span>
            </div>

            {{-- Advertencia --}}
            @if ($warning)
                <p
                    class="mt-4 text-sm font-medium
                           text-red-600 dark:text-red-400"
                >
                    {{ $warning }}
                </p>
            @endif

            {{-- Acciones --}}
            <div
                class="mt-7 flex flex-col-reverse gap-3
                       sm:flex-row sm:justify-center"
            >
                <button
                    type="button"
                    @click="open = false"
                    class="inline-flex min-w-28 items-center justify-center
                           rounded-lg border border-gray-300 bg-white
                           px-4 py-2.5 text-sm font-medium text-gray-700
                           shadow-sm transition hover:bg-gray-50
                           focus:outline-none focus:ring-2
                           focus:ring-gray-300 focus:ring-offset-2
                           dark:border-gray-700 dark:bg-gray-800
                           dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    {{ $cancelText }}
                </button>

                <button
                    type="button"
                    :disabled="payload.id === null"
                    @click="
                        $dispatch(confirmEvent, { id: payload.id });
                        open = false;
                    "
                    class="inline-flex min-w-28 items-center justify-center
                           rounded-lg bg-red-600 px-4 py-2.5
                           text-sm font-medium text-white shadow-sm
                           transition hover:bg-red-700
                           focus:outline-none focus:ring-2
                           focus:ring-red-500 focus:ring-offset-2
                           disabled:cursor-not-allowed disabled:opacity-50
                           dark:focus:ring-offset-gray-900"
                >
                    {{ $confirmText }}
                </button>
            </div>

        </div>
    </x-ui.modal>
</div>