<?php

declare(strict_types=1);

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rut',
        'name',
        'apellido_paterno',
        'apellido_materno',
        'movil',
        'state',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}