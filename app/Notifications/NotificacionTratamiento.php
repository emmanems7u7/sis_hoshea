<?php

namespace App\Notifications;

use App\Models\Tratamiento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificacionTratamiento extends Notification
{
    use Queueable;
    protected $tratamiento;
    /**
     * Create a new notification instance.
     */
    public function __construct(Tratamiento $tratamiento)
    {
        $this->tratamiento = $tratamiento;
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
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'El tratamiento ' . $this->tratamiento->nombre . ' dará inicio el dia de hoy, tome sus precauciones.',
            'action_url' => route('tratamientos.index', [
            ]),


        ];
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
}
