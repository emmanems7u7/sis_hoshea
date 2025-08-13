<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use App\Models\ConfCorreo;
use Illuminate\Support\Facades\Schema;
class configuracion_correo extends Mailable
{
    public function __construct()
    {
        $this->setDynamicMailConfig();
    }

    protected function setDynamicMailConfig()
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
    }
}
