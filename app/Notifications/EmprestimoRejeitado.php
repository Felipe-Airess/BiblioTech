<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmprestimoRejeitado extends Notification
{
    use Queueable;

    protected $emprestimo;
    protected $motivo;

    public function __construct($emprestimo, $motivo = null)
    {
        $this->emprestimo = $emprestimo;
        $this->motivo = $motivo;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $titulo = $this->emprestimo->livro?->titulo ?? 'livro';
        $msg = "Seu pedido de empréstimo do livro '" . $titulo . "' foi rejeitado.";
        if ($this->motivo) {
            $msg .= ' Motivo: ' . $this->motivo;
        }

        return [
            'type' => 'emprestimo_rejeitado',
            'emprestimo_id' => $this->emprestimo->id ?? null,
            'title' => 'Empréstimo rejeitado',
            'message' => $msg,
        ];
    }
}
<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Emprestimos;

class EmprestimoRejeitado extends Notification
{
    public function __construct(public Emprestimos $emprestimo) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $livro = $this->emprestimo->livro;
        $motivo = $this->emprestimo->rejected_reason;

        $mail = (new MailMessage)
            ->subject('Solicitacao de emprestimo rejeitada')
            ->greeting("Ola, {$notifiable->nome}!")
            ->line("Sua solicitacao do livro **{$livro->titulo}** foi rejeitada.")
            ->action('Ver meus emprestimos', route('emprestimos.historico'))
            ->line('Se tiver duvidas, procure a biblioteca.');

        if ($motivo) {
            $mail->line("Motivo: {$motivo}");
        }

        return $mail->salutation('Equipe BiblioTech');
    }
}
