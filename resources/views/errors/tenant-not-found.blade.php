<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subdominio no encontrado</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="text-center px-4">
        <h1 class="text-6xl font-bold text-gray-300 mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Sitio no encontrado</h2>
        <p class="text-gray-600 mb-6">
            El dominio <span class="font-mono bg-gray-200 px-2 py-1 rounded">{{ request()->getHost() }}</span> no está registrado.
        </p>
        {{-- <a href="https://aprsoft.cl" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
            Ir al sitio principal
        </a> --}}
    </div>
</body>
</html>