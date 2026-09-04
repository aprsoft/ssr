@extends('layouts.central.app')

@section('content')

<x-common.page-breadcrumb pageTitle="Inquilinos" />

<div class="space-y-6">


    <div class="border-b border-gray-200 dark:border-gray-800">
        <nav class="-mb-px flex gap-6" aria-label="Tabs">

            <a
                href="{{ route('central.tenants.index', ['status' => 'active']) }}"
                class="whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition
                    {{ $status === 'active'
                        ? 'border-brand-500 text-brand-500'
                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                    }}"
            >
                Activos
            </a>

            <a
                href="{{ route('central.tenants.index', ['status' => 'suspended']) }}"
                class="whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition
                    {{ $status === 'suspended'
                        ? 'border-brand-500 text-brand-500'
                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                    }}"
            >
                Suspendidos
            </a>

            <a
                href="{{ route('central.tenants.index') }}"
                class="whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition
                    {{ empty($status)
                        ? 'border-brand-500 text-brand-500'
                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                    }}"
            >
                Todos
            </a>

        </nav>
    </div>

    <livewire:central.tenant.tenant-table
        :status="$status"
    />

</div>

@endsection