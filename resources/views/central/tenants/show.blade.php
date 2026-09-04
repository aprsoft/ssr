@extends('layouts.central.app')

@section('content')

    <x-common.page-breadcrumb pageTitle="Detalle del Inquilino" />

    <div class="space-y-6">

        {{-- Mensajes --}}
        @session('success')
            <x-ui.alert variant="success">
                {{ $value }}
            </x-ui.alert>
        @endsession

        @session('error')
            <x-ui.alert variant="error">
                {{ $value }}
            </x-ui.alert>
        @endsession


        {{-- Información general --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        Información del Inquilino
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Información general y dominios asociados al tenant.
                    </p>
                </div>

                <div class="flex items-center gap-3">

                    <a
                        href="{{ route('central.tenants.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]"
                    >
                        Volver
                    </a>

                    <a
                        href="{{ route('central.tenants.edit', $tenant) }}"
                        class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600"
                    >
                        Editar
                    </a>

                </div>

            </div>


            {{-- Datos --}}
            <div class="mt-6 border-t border-gray-100 pt-6 dark:border-gray-800">

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                    {{-- ID --}}
                    <div>
                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            ID del Tenant
                        </p>

                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ $tenant->id }}
                        </p>
                    </div>


                    {{-- Estado --}}
                    <div>
                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Estado
                        </p>

                        @if ($tenant->trashed())
                            <span class="inline-flex rounded-full bg-error-50 px-2.5 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                                Suspendido
                            </span>
                        @else
                            <span class="inline-flex rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                                Activo
                            </span>
                        @endif
                    </div>


                    {{-- Fecha creación --}}
                    <div>
                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Fecha de creación
                        </p>

                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ $tenant->created_at?->format('d/m/Y H:i') ?? '-' }}
                        </p>
                    </div>


                    {{-- Última actualización --}}
                    <div>
                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Última actualización
                        </p>

                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ $tenant->updated_at?->format('d/m/Y H:i') ?? '-' }}
                        </p>
                    </div>

                </div>

            </div>

        </div>


        {{-- Dominios --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">

            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Dominios
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Dominios asociados al inquilino.
                </p>
            </div>


            <div class="mt-6">

                @forelse ($tenant->domains as $domain)

                    <div class="flex items-center justify-between border-b border-gray-100 py-4 last:border-b-0 dark:border-gray-800">

                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                {{ $domain->domain }}
                            </p>
                        </div>

                    </div>

                @empty

                    <div class="rounded-lg border border-gray-200 px-4 py-5 text-center dark:border-gray-800">

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Este tenant no tiene dominios asociados.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

@endsection
```
