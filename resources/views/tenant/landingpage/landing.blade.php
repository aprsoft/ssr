<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSR : {{ tenant()->id }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans">

    <nav class="flex items-center justify-between px-8 py-4 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <span class="text-lg font-medium text-gray-900">{{ tenant('name') }}</span>
        </div>
        <a href="{{ route('customer.login') }}" class="text-sm font-medium border border-green-700 text-green-700 px-5 py-2 rounded-lg hover:bg-green-50 transition">
            Ingresar al panel de clientes
        </a>
    </nav>

    <section class="flex flex-col items-center justify-center text-center px-6 py-24 gap-6">
        <h1 class="text-4xl font-medium text-gray-900">Bienvenido a SSR * {{ tenant()->id }}</h1>
        <p class="text-base text-gray-500 max-w-lg leading-relaxed">Servicio Sanitario Rural</p>
        <a href="{{ route('customer.login') }}" class="mt-2 bg-green-700 text-white text-sm font-medium px-6 py-3 rounded-lg hover:bg-green-800 transition">
            Ingresar al panel clientes
        </a>
    </section>

    <footer class="text-center py-6 border-t border-gray-100 text-xs text-gray-400">
        &copy; {{ date('Y') }} * APRSOFT. Todos los derechos reservados.
    </footer>

</body>
</html>