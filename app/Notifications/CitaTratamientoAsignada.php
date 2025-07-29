<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Cita;
use App\Models\Tratamiento;

class CitaTratamientoAsignada extends Notification
{
    use Queueable;
    protected $cita;
    /**
     * Create a new notification instance.
     */
    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $notifiable->preferredNotificationChannels();

    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable)
    {

        $tratamiento = Tratamiento::find($this->cita->tratamiento_id);

        return [
            'message' => 'Tiene una Cita asignada para la fecha  ' . $this->cita->fecha_hora->format('d-m-Y') . ' a horas ' . $this->cita->fecha_hora->format('H:i') . ', esta cita esta asociada al tratamiento ' . $tratamiento->nombre . ', verifique desde el panel adminstrativo de tratamientos',
            'action_url' => route('tratamientos.index', [
            ]),
        ];
    }
}
