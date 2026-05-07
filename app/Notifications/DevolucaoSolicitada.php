<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DevolucaoSolicitada extends Notification
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
            'type' => 'devolucao_solicitada',
            'emprestimo_id' => $this->emprestimo->id ?? null,
            'title' => 'Devolução solicitada',
            'message' => "Foi registrada uma solicitação de devolução para o livro '" . $titulo . "'.",
        ];
    }
}
