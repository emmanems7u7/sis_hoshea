<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Inventario;
class StockAlerta extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $inventario;
    protected $nivel; // puede ser 'bajo' o 'crítico'

    public function __construct(Inventario $inventario, string $nivel)
    {
        $this->inventario = $inventario;
        $this->nivel = $nivel;
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

    public function toDatabase($notifiable): array
    {
        return [
            'titulo' => 'Alerta de Inventario',
            'message' => 'El stock del ítem "' . $this->inventario->nombre . '" está en nivel ' . strtoupper($this->nivel),
            'stock_actual' => $this->inventario->stock_actual,
            'stock_minimo' => $this->inventario->stock_minimo,
            'ubicacion' => $this->inventario->ubicacion,
            'action_url' => route('inventario.edit', $this->inventario),
            'nivel' => $this->nivel,
        ];

    }
}
