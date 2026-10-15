<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 lg:gap-8">
        <section class="rounded-xl border border-gray-200 p-6 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Datos del usuario
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Información de la cuenta central.
            </p>

            <dl class="mt-6 divide-y divide-gray-200 dark:divide-gray-700">
                <div class="py-3">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Nombre</dt>
                    <dd class="mt-1 font-medium text-gray-900 dark:text-white">
                        {{ $user->name ?: 'No registrado' }}
                    </dd>
                </div>
                <div class="py-3">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Correo electrónico</dt>
                    <dd class="mt-1 break-all font-medium text-gray-900 dark:text-white">
                        {{ $user->email ?: 'No registrado' }}
                    </dd>
                </div>
                <div class="py-3">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Verificación del correo</dt>
                    <dd class="mt-1 font-medium text-gray-900 dark:text-white">
                        {{ $user->email_verified_at ? 'Verificado' : 'Pendiente' }}
                    </dd>
                </div>
                <div class="py-3">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Fecha de creación</dt>
                    <dd class="mt-1 font-medium text-gray-900 dark:text-white">
                        {{ $user->created_at?->format('d/m/Y H:i') ?? 'No disponible' }}
                    </dd>
                </div>
                <div class="pt-3">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Última actualización</dt>
                    <dd class="mt-1 font-medium text-gray-900 dark:text-white">
                        {{ $user->updated_at?->format('d/m/Y H:i') ?? 'No disponible' }}
                    </dd>
                </div>
            </dl>
        </section>

        <section class="rounded-xl border border-gray-200 p-6 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Roles asignados
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Roles de acceso de este usuario en el sistema central.
            </p>

            @if ($user->roles->isEmpty())
                <p class="mt-6 rounded-lg border border-dashed border-gray-300 p-4 text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                    Este usuario no tiene roles asignados.
                </p>
            @else
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach ($user->roles as $role)
                        <span class="inline-flex items-center rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm font-medium text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <div class="flex justify-end">
        <a
            href="{{ route('central.users.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
        >
            Volver a usuarios
        </a>
    </div>
</div>
