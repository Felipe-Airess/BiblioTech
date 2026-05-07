<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmprestimoSolicitado extends Notification
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
            'type' => 'emprestimo_solicitado',
            'emprestimo_id' => $this->emprestimo->id ?? null,
            'title' => 'Novo pedido de aluguel',
            'message' => "Um membro solicitou o aluguel do livro '{$titulo}'.",
        ];
    }
}
