<?php

namespace App\Services\Error;

use App\Models\ErrorLog;
use Illuminate\Support\Facades\Log;
use Throwable;

class ErrorLogger
{
    /**
     * Registra una excepción en el log de Laravel y,
     * cuando sea posible, en la tabla central error_logs.
     */
    public function report(Throwable $exception, array $context = []): void
    {
        Log::error($exception->getMessage(), [
            'exception' => $exception,
            'context' => $context,
        ]);

        try {
            $centralConnection = config('tenancy.database.central_connection');

            if (! is_string($centralConnection) || $centralConnection === '') {
                Log::warning(
                    'No fue posible persistir la excepción en error_logs: conexión central no configurada.',
                    [
                        'exception_class' => get_class($exception),
                    ]
                );

                return;
            }

            $errorLog = new ErrorLog();

            // ErrorLog es información central.
            // No debe depender de la conexión activa del tenant.
            $errorLog->setConnection($centralConnection);

            $errorLog->fill([
                'message' => $exception->getMessage(),
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'errors' => $context ?: null,
            ]);

            $errorLog->save();
        } catch (Throwable $loggingException) {
            /*
             * El sistema de logging nunca debe reemplazar
             * u ocultar la excepción original.
             */
            Log::critical(
                'Falló la persistencia de una excepción en error_logs.',
                [
                    'original_exception' => get_class($exception),
                    'original_message' => $exception->getMessage(),
                    'logging_exception' => get_class($loggingException),
                    'logging_message' => $loggingException->getMessage(),
                ]
            );
        }
    }
}