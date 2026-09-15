@extends('layouts.tenant.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Crear Permiso" />

    <div class="space-y-6">
        <livewire:tenant.permission.create-permission />
    </div>
@endsection