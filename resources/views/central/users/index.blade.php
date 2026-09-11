@extends('layouts.central.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Usuarios" />

    <div class="space-y-6">
        <livewire:central.user.user-table/> 
@endsection

