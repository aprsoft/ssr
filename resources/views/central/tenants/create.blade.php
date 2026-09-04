@extends('layouts.central.app')

@section('content')

    <x-common.page-breadcrumb pageTitle="Crear Inquilino" />

    <div class="space-y-6">
   

        {{-- Formulario --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6"
        >

            {{-- Encabezado --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Registrar nuevo Inquilino
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Ingresa el identificador que será utilizado para crear el tenant.
                </p>
            </div>


            <form
                method="POST"
                action="{{ route('central.tenants.store') }}"
                class="mt-6"
            >
                @csrf

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
                            name="id"
                            id="id"
                            value="{{ old('id') }}"
                            maxlength="20"
                            required
                            autofocus
                            autocomplete="off"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs outline-none placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        >

                        @error('id')
                            <p class="mt-1.5 text-xs text-error-500">
                                {{ $message }}
                            </p>
                        @enderror

                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                            Máximo 20 caracteres. Utiliza solo letras, números, guiones y guiones bajos.
                        </p>
                    </div>

                </div>


                {{-- Acciones --}}
                <div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                    <a
                        href="{{ route('central.tenants.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600"
                    >
                        Crear Inquilino
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection