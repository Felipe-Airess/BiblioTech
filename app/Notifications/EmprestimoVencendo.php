<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Emprestimos;

class EmprestimoVencendo extends Notification
{
    public function __construct(public Emprestimos $emprestimo) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $livro = $this->emprestimo->livro;
        $prazo = $this->emprestimo->data_devolucao_prevista->format('d/m/Y');

        return (new MailMessage)
            ->subject('⏰ Lembrete BiblioTech — Devolução amanhã!')
            ->greeting("Olá, {$notifiable->nome}!")
            ->line("O prazo de devolução do livro **{$livro->titulo}** é **amanhã ({$prazo})**.")
            ->line('Evite multas devolvendo o livro na biblioteca até o fim do dia.')
            ->action('Ver meus empréstimos', route('emprestimos.historico'))
            ->line('Obrigado por usar o BiblioTech!')
            ->salutation('Equipe BiblioTech');
    }
}