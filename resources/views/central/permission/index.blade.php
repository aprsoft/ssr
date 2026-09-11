@extends('layouts.central.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Permisos" />

    <div>
        Permisos
    </div>

    <div class="space-y-6">
        <livewire:central.permission.permission-table />
    </div>

    <x-ui.confirm-modal
        open-event="open-permission-delete-modal"
        confirm-event="permission-destroy-confirmed"
        title="Eliminar permiso"
        message="¿Está seguro de que desea eliminar este permiso?"
        warning="Esta acción no se puede deshacer."
        confirm-text="Eliminar"
        cancel-text="Cancelar"
    />
@endsection