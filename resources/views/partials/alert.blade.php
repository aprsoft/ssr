{{-- Éxito --}}
@if (session('success'))
    <x-ui.alert variant="success">
        {{ session('success') }}
    </x-ui.alert>
@endif

{{-- Errores de validación --}}
@if ($errors->any())
    <x-ui.alert variant="error">
        <ul class="list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-ui.alert>
@endif

{{-- Error general --}}
@if (session('error'))
    <x-ui.alert variant="error">
        {{ session('error') }}
    </x-ui.alert>
@endif