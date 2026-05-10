<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservaCancelada extends Notification
{
    use Queueable;

    public function __construct(protected $reserva)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $titulo = $this->reserva->livro?->titulo ?? 'livro';
        $membro = $this->reserva->membro?->nome ?? 'Um membro';

        return [
            'type' => 'reserva_cancelada',
            'reserva_id' => $this->reserva->id ?? null,
            'livro_id' => $this->reserva->livro_id ?? null,
            'title' => 'Reserva cancelada',
            'message' => "{$membro} cancelou a reserva do livro '{$titulo}'.",
        ];
    }
}
