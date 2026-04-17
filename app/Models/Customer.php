<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;


class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos permitidos para asignación masiva
     */
    protected $fillable = [
        'rut',
        'name',
        'email',
        'password',
        // 'phone',
        // 'address',
        // 'is_active',
    ];

    /**
     * Campos ocultos en arrays/JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting de tipos
     */
    protected $casts = [
        'password' => 'hashed',      // Laravel hashea automáticamente
        
    ];

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /**
     * Mutator: Limpia el RUT antes de guardar
     * Ej: "12.345.678-9" → "123456789"
     */
    // public function setRutAttribute(string $value): void
    // {
    //     $this->attributes['rut'] = str_replace(['.', '-'], '', strtolower($value));
    // }

    /**
     * Accessor: Formatea el RUT para mostrar
     * Ej: "123456789" → "12.345.678-9"
     */
    public function getRutFormateadoAttribute(): string
    {
        $rut = $this->rut;
        
        if (strlen($rut) < 2) {
            return $rut;
        }

        $dv = substr($rut, -1);
        $numero = substr($rut, 0, -1);
        
        return number_format((int)$numero, 0, '', '.') . '-' . strtoupper($dv);
    }
}