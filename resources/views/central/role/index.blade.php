@extends('layouts.central.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Roles" />

     <div class="space-y-6">
        roles
    </div>

    <div class="space-y-6">
        @session('success')
            <x-ui.alert variant="success">
                {{ $value }}
            </x-ui.alert>
        @endsession     

        <livewire:central.role.role-table/> 
    </div>
@endsection