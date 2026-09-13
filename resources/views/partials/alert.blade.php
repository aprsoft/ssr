@php
    $realtimeChannel = null;

    if (tenant() !== null && auth('tenant')->check()) {
        $realtimeChannel = sprintf(
            'ssr.tenant.%s.user.%s',
            tenant()->getTenantKey(),
            auth('tenant')->id()
        );
    } elseif (tenant() === null && auth('web')->check()) {
        $realtimeChannel = sprintf(
            'ssr.central.user.%s',
            auth('web')->id()
        );
    }
@endphp

<div
    x-data="{
        realtimeSuccess: null,
        realtimeTimer: null,

        showRealtimeSuccess(message) {
            if (! message) {
                return;
            }

            this.realtimeSuccess = message;

            clearTimeout(this.realtimeTimer);

            this.realtimeTimer = setTimeout(() => {
                this.realtimeSuccess = null;
            }, 7000);
        }
    }"
    x-on:ssr-realtime-success.window="
        showRealtimeSuccess($event.detail.message)
    "
    class="space-y-3"
>
    {{-- Éxito de sesión --}}
    @if (session('success'))
        <div
            x-data="{ visible: true }"
            x-init="setTimeout(() => visible = false, 5000)"
            x-show="visible"
            x-transition
        >
            <x-ui.alert variant="success">
                {{ session('success') }}
            </x-ui.alert>
        </div>
    @endif

    {{-- Éxito recibido por Reverb --}}
    <div
        x-show="realtimeSuccess"
        x-transition
        style="display: none;"
    >
        <x-ui.alert variant="success">
            <span x-text="realtimeSuccess"></span>
        </x-ui.alert>
    </div>

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
        <div
            x-data="{ visible: true }"
            x-init="setTimeout(() => visible = false, 8000)"
            x-show="visible"
            x-transition
        >
            <x-ui.alert variant="error">
                {{ session('error') }}
            </x-ui.alert>
        </div>
    @endif
</div>

@if ($realtimeChannel)
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (! window.Echo) {
                console.error(
                    'Laravel Echo no está disponible para las notificaciones realtime.'
                );

                return;
            }

            window.Echo
                .private(@js($realtimeChannel))
                .listen('.user-email-sent', (event) => {
                    window.dispatchEvent(
                        new CustomEvent(
                            'ssr-realtime-success',
                            {
                                detail: {
                                    message: event.message,
                                },
                            }
                        )
                    );
                });
        });
    </script>
@endif