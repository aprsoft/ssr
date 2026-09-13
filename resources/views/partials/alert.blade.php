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
        realtimeType: null,
        realtimeMessage: null,
        realtimeTimer: null,

        showRealtime(type, message) {
            if (
                ! ['success', 'error'].includes(type)
                || ! message
            ) {
                return;
            }

            this.realtimeType = type;
            this.realtimeMessage = message;

            clearTimeout(this.realtimeTimer);

            const timeout = type === 'error'
                ? 8000
                : 7000;

            this.realtimeTimer = setTimeout(() => {
                this.realtimeType = null;
                this.realtimeMessage = null;
            }, timeout);
        }
    }"

    x-on:ssr-job-notification.window="
        showRealtime(
            $event.detail.type,
            $event.detail.message
        )
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

    {{-- Notificación realtime de éxito --}}
    <div
        x-show="
            realtimeMessage
            && realtimeType === 'success'
        "
        x-transition
        style="display: none;"
    >
        <x-ui.alert variant="success">
            <span x-text="realtimeMessage"></span>
        </x-ui.alert>
    </div>

    {{-- Notificación realtime de error --}}
    <div
        x-show="
            realtimeMessage
            && realtimeType === 'error'
        "
        x-transition
        style="display: none;"
    >
        <x-ui.alert variant="error">
            <span x-text="realtimeMessage"></span>
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
        document.addEventListener(
            'DOMContentLoaded',
            () => {
                if (! window.Echo) {
                    console.error(
                        'Laravel Echo no está disponible.'
                    );

                    return;
                }

                window.Echo
                    .private(@js($realtimeChannel))
                    .listen(
                        '.job-notification',
                        (event) => {
                            window.dispatchEvent(
                                new CustomEvent(
                                    'ssr-job-notification',
                                    {
                                        detail: {
                                            type: event.type,
                                            message: event.message,
                                        },
                                    }
                                )
                            );
                        }
                    );
            }
        );
    </script>
@endif