<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\NotificarTratamientosDelDia;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command(NotificarTratamientosDelDia::class)
    ->dailyAt('23:00')
    ->withoutOverlapping()
    ->onOneServer();