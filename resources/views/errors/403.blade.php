@php
    // Mantiene el diseño existente de SSR para el dominio central y los tenants.
    $isTenant = tenancy()->initialized ?? false;
    $layout = $isTenant
        ? 'layouts.tenant.fullscreen-layout'
        : 'layouts.central.fullscreen-layout';
    $dashboardRoute = $isTenant ? 'tenant.dashboard' : 'central.dashboard';
    $homeUrl = \Illuminate\Support\Facades\Route::has($dashboardRoute)
        ? route($dashboardRoute)
        : url('/');
    $title = 'Acceso denegado';
@endphp

@extends($layout)

@section('content')
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden p-6">
        <x-common.common-grid-shape />

        <main class="relative z-10 mx-auto w-full max-w-lg rounded-2xl border border-gray-200 bg-white px-6 py-12 text-center shadow-theme-lg dark:border-gray-800 dark:bg-gray-900 sm:px-10">
            <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-error-50 text-error-600 dark:bg-error-500/10 dark:text-error-400">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" />
                    <path d="M12 8v5" />
                    <path d="M12 17h.01" />
                </svg>
            </div>

            <p class="mb-3 text-sm font-semibold tracking-widest text-error-600 dark:text-error-400">ERROR 403</p>
            <h1 class="mb-4 text-3xl font-bold text-gray-900 dark:text-white">Acceso denegado</h1>
            <p class="mx-auto mb-8 max-w-sm text-sm leading-6 text-gray-600 dark:text-gray-400 sm:text-base">
                No tienes autorización para acceder a esta sección o realizar esta operación.
                Si consideras que necesitas acceso, contacta al administrador del sistema.
            </p>

            <a href="{{ $homeUrl }}"
               class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-6 py-3 text-sm font-semibold text-white shadow-theme-xs transition hover:bg-brand-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-500">
                Volver al panel principal
            </a>
        </main>

        <p class="absolute bottom-6 left-1/2 -translate-x-1/2 text-center text-xs text-gray-500 dark:text-gray-400">
            &copy; {{ date('Y') }} - APRSOFT
        </p>
    </div>
@endsection
