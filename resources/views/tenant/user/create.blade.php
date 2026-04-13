@extends('layouts.tenant.app')

@section('content')
    <x-common.page-breadcrumb pageTitle={{$title}} />

    <div class="space-y-6">
        @session('success')
            <x-ui.alert variant="success">
                {{ $value }}
            </x-ui.alert>
        @endsession
      
        <livewire:tenant.user.user-create/>
      
@endsection

