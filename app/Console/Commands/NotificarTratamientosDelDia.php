<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tratamiento;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarNotificacionPaciente;
use App\Mail\EnviarNotificacionPersonal;
use Illuminate\Support\Facades\Schema;


use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\ConfCorreo;
use App\Notifications\NotificacionTratamiento;

class NotificarTratamientosDelDia extends Command
{
    protected $signature = 'tratamientos:notificar-hoy';
    protected $description = 'Envía notificaciones a pacientes y personal de los tratamientos que inician hoy';

    public function handle(): int
    {

        $dbName = env('DB_DATABASE');
        $tableName = 'conf_correos';
        if (Schema::connection('mysql')->hasTable($dbName . '.' . $tableName)) {
            $config = ConfCorreo::first();

            if ($config) {
                config([
                    'mail.mailers.smtp.host' => $config->host,
                    'mail.mailers.smtp.port' => $config->port,
                    'mail.mailers.smtp.encryption' => $config->encryption ?: null,
                    'mail.mailers.smtp.username' => $config->username,
                    'mail.mailers.smtp.password' => $config->password,
                    'mail.from.address' => $config->from_address,
                    'mail.from.name' => $config->from_name,
                ]);
            }
        }


        $hoy = Carbon::today();

        Tratamiento::whereDate('fecha_inicio', $hoy)
            ->with(['paciente:id,email', 'citas.usuarios'])
            ->chunkById(50, function ($coleccion) {
                foreach ($coleccion as $tratamiento) {


                    if ($tratamiento->paciente?->email) {
                        Mail::to($tratamiento->paciente->email)
                            ->queue(new EnviarNotificacionPaciente($tratamiento));
                        Log::info("Email enviado a: {$tratamiento->paciente->email}");
                    }

                    $destinatarios = $tratamiento->citas
                        ->flatMap(fn($cita) => $cita->usuarios)
                        ->unique('id');

                    $destinatarios->each(function ($usuario) use ($tratamiento) {

                        Mail::to($usuario->email)
                            ->queue(new EnviarNotificacionPersonal($tratamiento, $usuario));

                        $usuario->notify(new NotificacionTratamiento($tratamiento));

                        Log::info("Email enviado a: {$usuario->email}");
                    });
                }
            });

        $this->info('Notificaciones enviadas.');
        return Command::SUCCESS;
    }
}
