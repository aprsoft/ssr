@extends('layouts.central.app')

@section('content')

    <x-common.page-breadcrumb pageTitle="Editar Inquilino" />

    <div class="space-y-6">
        <livewire:central.tenant.edit-tenant
            :tenant-id="$tenant->id"
        />
    </div>

@endsection