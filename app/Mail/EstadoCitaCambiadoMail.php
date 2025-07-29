<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EstadoCitaCambiadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cita;

    /**
     * Create a new message instance.
     */
    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Actualización del estado de tu cita'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {


        return new Content(
            view: 'emails.estado_cita_cambiado',
            with: [
                'cita' => $this->cita

            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}
