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

        $conf = ConfCorreo::first();


        config([
            'mail.mailers.smtp.host' => $conf->host,
            'mail.mailers.smtp.port' => $conf->port,
            'mail.mailers.smtp.username' => $conf->username,
            'mail.mailers.smtp.password' => $conf->password,
            'mail.mailers.smtp.encryption' => $conf->encryption,
            'mail.default' => $conf->mailer,

            'mail.from.address' => $conf->from_address,
            'mail.from.name' => $conf->from_name,
        ]);

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
