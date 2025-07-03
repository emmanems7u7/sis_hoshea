<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use App\Models\ConfCorreo;

class configuracion_correo extends Mailable
{
    public function __construct()
    {
        $this->setDynamicMailConfig();
    }

    protected function setDynamicMailConfig()
    {
        $conf = ConfCorreo::first();

        if ($conf) {
            config([
                'mail.mailers.smtp.host' => $conf->conf_smtp_host,
                'mail.mailers.smtp.port' => $conf->conf_smtp_port,
                'mail.mailers.smtp.username' => $conf->conf_smtp_user,
                'mail.mailers.smtp.password' => $conf->conf_smtp_pass,
                'mail.mailers.smtp.encryption' => $conf->conf_protocol,
                'mail.default' => 'smtp',
            ]);
        }
    }
}
