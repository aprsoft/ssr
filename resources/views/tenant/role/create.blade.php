@extends('layouts.tenant.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Crear Rol" />

    <div class="space-y-6">
        <livewire:tenant.role.create-role />
    </div>
@endsection