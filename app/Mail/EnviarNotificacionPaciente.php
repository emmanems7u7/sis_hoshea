<?php

namespace App\Mail;

use App\Models\Tratamiento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnviarNotificacionPaciente extends configuracion_correo
{
    use Queueable, SerializesModels;

    private $tratamiento;



    /**
     * Create a new message instance.
     */
    public function __construct(Tratamiento $tratamiento)
    {
        $this->tratamiento = $tratamiento;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notificacion de inicio de tratamiento',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notificacion_paciente',
            with: [
                'tratamiento' => $this->tratamiento,
            ],
        );


    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
