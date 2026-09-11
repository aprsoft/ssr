@extends('layouts.central.app')

@section('content')

    <x-common.page-breadcrumb pageTitle="Crear Inquilino" />

    <div class="space-y-6">
        <livewire:central.tenant.create-tenant />
    </div>

@endsection