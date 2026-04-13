@extends('layouts.central.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Crear Inquilino" />



        <div class="bg-white shadow-md rounded-lg w-full max-w-3xl p-6">

            <h1 class="text-2xl font-bold text-gray-800 mb-6">
                Registrar nuevo Tenant
            </h1>
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('central.tenants.store') }}" class="space-y-4">
                @csrf

                <!-- Campo ID -->
                <div>
                    <label for="id" class="block text-gray-700 font-semibold mb-1">
                        ID del Tenant
                    </label>

                    <input type="text"
                           name="id"
                           id="id"
                           required
                           class="w-full border border-gray-300 rounded px-3 py-2
                                  focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-2 pt-4">

                    <!-- Crear -->
                    <button type="submit"
                        class="px-6 py-2 rounded bg-green-600 text-white font-semibold
                               hover:bg-green-700 transition">
                        Crear
                    </button>

                    <!-- Volver -->
                    <a href="{{ route('central.tenants.index') }}"
                        class="px-6 py-2 rounded bg-gray-600 text-white font-semibold
                               hover:bg-gray-700 transition">
                        Volver
                    </a>
                </div>

            </form>

        </div>

  


@endsection