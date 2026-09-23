<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $guarded = ['id'];

    protected $casts = [          
        'errors' => 'array',
    ];
}
