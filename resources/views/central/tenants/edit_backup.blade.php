```blade
@extends('layouts.central.app')

@section('content')

    <x-common.page-breadcrumb pageTitle="Editar Inquilino" />

    <div class="space-y-6">

        {{-- Mensajes --}}
        @if (session('success'))
            <x-ui.alert variant="success">
                {{ session('success') }}
            </x-ui.alert>
        @endif

        @if (session('error'))
            <x-ui.alert variant="error">
                {{ session('error') }}
            </x-ui.alert>
        @endif


        {{-- Formulario --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">

            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Editar Inquilino
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Modifica la información disponible del tenant.
                </p>
            </div>


            <form
                method="POST"
                action="{{ route('central.tenants.update', $tenant) }}"
                class="mt-6"
            >
                @csrf
                @method('PUT')


                <div class="grid grid-cols-1 gap-6">

                    {{-- ID del Tenant --}}
                    <div>
                        <label
                            for="id"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                        >
                            ID del Tenant
                        </label>

                        <input
                            type="text"
                            id="id"
                            value="{{ $tenant->id }}"
                            disabled
                            class="h-11 w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700 shadow-theme-xs outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400"
                        >

                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                            El identificador del tenant no se modifica desde este formulario.
                        </p>
                    </div>


                    {{-- Dominio --}}
                    <div>
                        <label
                            for="domain"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                        >
                            Dominio
                        </label>

                        <input
                            type="text"
                            name="domain"
                            id="domain"
                            value="{{ old('domain', $tenant->domains->first()?->domain) }}"
                            required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs outline-none placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        >

                        @error('domain')
                            <p class="mt-1.5 text-xs text-error-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>


                {{-- Botones --}}
                <div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                    <a
                        href="{{ route('central.tenants.show', $tenant) }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600"
                    >
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
```
