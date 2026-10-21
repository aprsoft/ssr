@extends('layouts.central.app')

@section('content')
    <x-common.page-breadcrumb pageTitle={{$title}} />

    <div class="space-y-6">      
      
        <livewire:central.user.user-show/>

    </div>  
@endsection

