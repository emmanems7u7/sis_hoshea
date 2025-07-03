<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Tratamiento;
use App\Models\User;


class EnviarNotificacionPersonal extends configuracion_correo
{
    use Queueable, SerializesModels;

    private $tratamiento;
    private $usuario;
    /**
     * Create a new message instance.
     */
    public function __construct(Tratamiento $tratamiento, User $user)
    {
        $this->tratamiento = $tratamiento;
        $this->usuario = $user;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notificación Personal',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notificacion_personal',
            with: [
                'tratamiento' => $this->tratamiento,
                'usuario' => $this->usuario,

            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
