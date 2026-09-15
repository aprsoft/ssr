@extends('layouts.tenant.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Editar Permiso" />

    <div class="space-y-6">
        <livewire:tenant.permission.edit-permission
            :permission-id="$permission->id"
        />
    </div>
@endsection