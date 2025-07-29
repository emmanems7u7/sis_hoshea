<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tratamiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\TratamientosFinalizadosMail;
class FinalizarTratamientosVencidos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tratamientos:finalizar-vencidos';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Finaliza automáticamente los tratamientos cuya fecha de fin ya ha pasado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hoy = Carbon::today();

        $tratamientos = Tratamiento::whereNotNull('fecha_fin')
            ->whereDate('fecha_fin', '<', $hoy)
            ->where('estado', '!=', 'finalizado')
            ->get();

        $contador = 0;

        foreach ($tratamientos as $tratamiento) {
            $tratamiento->estado = 'finalizado';

            $tratamiento->observaciones = !empty($tratamiento->observaciones)
                ? $tratamiento->observaciones . ' | Finalizado automáticamente el ' . now()->format('d/m/Y')
                : 'Finalizado automáticamente el ' . now()->format('d/m/Y');

            $tratamiento->save();
            $contador++;
        }


        if ($contador > 0) {
            $adminsEmails = User::role('admin')->pluck('email')->toArray();

            if (!empty($adminsEmails)) {
                Mail::to($adminsEmails)->send(new TratamientosFinalizadosMail($contador));
            }
        }

        $this->info("Se finalizaron automáticamente $contador tratamientos vencidos.");
    }
}
