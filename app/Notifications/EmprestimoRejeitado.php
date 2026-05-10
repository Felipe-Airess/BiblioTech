<?php

namespace App\Notifications;

use App\Models\Emprestimos;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmprestimoRejeitado extends Notification
{
    use Queueable;

    public function __construct(protected Emprestimos $emprestimo, protected ?string $motivo = null)
    {
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray($notifiable): array
    {
        $titulo = $this->emprestimo->livro?->titulo ?? 'livro';
        $motivo = $this->motivo ?: $this->emprestimo->rejected_reason;
        $message = "Seu pedido de empréstimo do livro '{$titulo}' foi rejeitado.";

        if ($motivo) {
            $message .= " Motivo: {$motivo}";
        }

        return [
            'type' => 'emprestimo_rejeitado',
            'emprestimo_id' => $this->emprestimo->id ?? null,
            'livro_id' => $this->emprestimo->livro_id ?? null,
            'title' => 'Empréstimo rejeitado',
            'message' => $message,
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        $livro = $this->emprestimo->livro;
        $motivo = $this->motivo ?: $this->emprestimo->rejected_reason;

        $mail = (new MailMessage)
            ->subject('Solicitação de empréstimo rejeitada')
            ->greeting('Olá, ' . ($notifiable->nome ?? $notifiable->name ?? 'leitor') . '!')
            ->line('Sua solicitação do livro "' . ($livro?->titulo ?? 'livro') . '" foi rejeitada.')
            ->action('Ver meus empréstimos', route('emprestimos.historico'))
            ->line('Se tiver dúvidas, procure a biblioteca.');

        if ($motivo) {
            $mail->line("Motivo: {$motivo}");
        }

        return $mail->salutation('Equipe BiblioTech');
    }
}
