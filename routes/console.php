<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('fresh', function () {
    $this->call('optimize:clear');
    $this->call('cache:clear');
    $this->call('config:clear');
    $this->call('route:clear');
    $this->call('view:clear');
    $this->call('config:cache');
    $this->call('route:cache');
    $this->call('view:cache');

    $this->info('¡Proyecto limpiado!');
})->describe('Limpieza completa del proyecto');
