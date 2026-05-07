<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmprestimoRetirado extends Notification
{
    use Queueable;

    protected $emprestimo;

    public function __construct($emprestimo)
    {
        $this->emprestimo = $emprestimo;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $titulo = $this->emprestimo->livro?->titulo ?? 'livro';
        return [
            'type' => 'emprestimo_retirado',
            'emprestimo_id' => $this->emprestimo->id ?? null,
            'title' => 'Empréstimo retirado',
            'message' => "Seu empréstimo do livro '" . $titulo . "' foi marcado como retirado.",
        ];
    }
}
