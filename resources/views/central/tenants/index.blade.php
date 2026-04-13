@extends('layouts.central.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Inquilinos" />

    <div class="space-y-6">
        @session('success')
            <x-ui.alert variant="success">
                {{ $value }}
            </x-ui.alert>
        @endsession

      <livewire:central.tenant.tenant-table/>
@endsection


