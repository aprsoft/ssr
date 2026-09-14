<?php

declare(strict_types=1);

namespace App\Services\Tenant;

use App\Models\Tenant\Employee;
use App\Models\Tenant\User;
use App\Services\Error\ErrorLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

final class EmployeeUserCreator
{
    public function __construct(
        private readonly ErrorLogger $errorLogger,
    ) {
    }

    public function create(
        array $employeeData,
        ?string $email = null
    ): Employee {
        try {
            $email = $this->normalizeEmail($email);

            return DB::connection('tenant')->transaction(
                function () use (
                    $employeeData,
                    $email
                ): Employee {
                    $user = new User();

                    $user->setConnection('tenant');

                    $user->fill([
                        'email' => $email,
                        'password' => Str::random(64),
                        'is_active' => false,
                    ]);

                    $user->save();

                    $employee = new Employee();

                    $employee->setConnection('tenant');

                    $employee->fill($employeeData);
                    $employee->user_id = $user->id;

                    $employee->save();

                    $employee->setRelation('user', $user);

                    return $employee;
                }
            );
        } catch (Throwable $exception) {
            $this->errorLogger->report($exception, [
                'operation' => 'tenant.employee.create',
                'tenant_id' => tenant()?->getTenantKey(),
                'employee' => $this->safeEmployeeContext($employeeData),
                'email' => $email,
            ]);

            throw $exception;
        }
    }

    private function normalizeEmail(?string $email): ?string
    {
        if ($email === null) {
            return null;
        }

        $email = Str::lower(trim($email));

        return $email !== '' ? $email : null;
    }

    private function safeEmployeeContext(array $employeeData): array
    {
        return [
            'rut' => $employeeData['rut'] ?? null,
            'name' => $employeeData['name'] ?? null,
            'state' => $employeeData['state'] ?? null,
        ];
    }
}