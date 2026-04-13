@extends('layouts.tenant.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Profile">
        <x-slot:breadcrumbs>
            <li>
                <a href="{{ route('tenant.dashboard') }}" class="text-gray-700 hover:text-brand-600 dark:text-gray-400 dark:hover:text-brand-500">Dashboard</a>
            </li>
            <li>
                <span class="text-gray-700 dark:text-gray-400">Profile</span>
            </li>
        </x-slot:breadcrumbs>
    </x-common.page-breadcrumb>

    <x-layouts.settings title="Perfil" description="Actualiza tus datos personales">
        @if (session('status'))
            <div class="mb-6">
                <x-ui.alert variant="success" :message="session('status')" />
            </div>
        @endif

        <form method="POST" action="{{ route('tenant.settings.profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name Input -->
            <div>
                <x-forms.input
                    name="name"
                    label="Name"
                    type="text"
                    :value="$user->name"
                    required
                    autofocus
                />
            </div>

            <!-- Email Input -->
            <div>
                <x-forms.input
                    name="email"
                    label="Email"
                    type="email"
                    :value="$user->email"
                    required
                />
            </div>

            <!-- Save Button -->
            <div>
                <x-ui.button type="submit" variant="primary">
                    Guardar
                </x-ui.button>
            </div>
        </form>

        <!-- Delete Account Section -->
        <div class="mt-8 border-t border-gray-200 pt-8 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Borrar Cuenta</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Elimina tu cuenta y todos sus recursos</p>

            <form method="POST" action="{{ route('tenant.settings.profile.destroy') }}" class="mt-4"
                  onsubmit="return confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.');">
                @csrf
                @method('DELETE')
                <x-ui.button
                    type="submit"
                    variant="primary"
                    className="bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800"
                >
                    Borrar Cuenta
                </x-ui.button>
            </form>
        </div>
    </x-layouts.settings>
@endsection
