{{-- Éxito --}}
@if (session('success'))
    <div class="bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-3 mb-4">
        {{ session('success') }}
    </div>
@endif

{{-- Errores de validación --}}
@if ($errors->any())
    <div class="bg-red-100 text-red-800 border border-red-300 rounded-lg px-4 py-3 mb-4">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Error general --}}
@if (session('error'))
    <div class="bg-red-100 text-red-800 border border-red-300 rounded-lg px-4 py-3 mb-4">
        {{ session('error') }}
    </div>
@endif