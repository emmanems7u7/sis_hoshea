<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\NotificarTratamientosDelDia;
use App\Console\Commands\FinalizarTratamientosVencidos;



Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command(NotificarTratamientosDelDia::class)
    ->dailyAt('20:57')
    ->withoutOverlapping()
    ->onOneServer();


Schedule::command(FinalizarTratamientosVencidos::class)
    ->dailyAt('15:24')
    ->withoutOverlapping()
    ->onOneServer();
