<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $guarded = ['id'];

    protected $casts = [          
        'errors' => 'array',
    ];
}
