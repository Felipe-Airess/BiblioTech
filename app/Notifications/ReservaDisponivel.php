<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservaDisponivel extends Notification
{
    use Queueable;

    public function __construct(protected $reserva, protected $emprestimo = null)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $titulo = $this->reserva->livro?->titulo ?? $this->emprestimo?->livro?->titulo ?? 'livro';

        return [
            'type' => 'reserva_disponivel',
            'reserva_id' => $this->reserva->id ?? null,
            'emprestimo_id' => $this->emprestimo->id ?? null,
            'livro_id' => $this->reserva->livro_id ?? $this->emprestimo?->livro_id,
            'title' => 'Reserva atendida',
            'message' => "Sua reserva do livro '{$titulo}' foi atendida. O empréstimo está aprovado e aguarda retirada.",
        ];
    }
}
