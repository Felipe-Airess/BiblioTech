<?php

namespace App\Notifications;

use App\Models\Membros;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PasswordHelpRequested extends Notification
{
    use Queueable;

    public function __construct(
        private string $email,
        private ?string $message = null,
        private ?Membros $membro = null,
    ) {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $nome = $this->membro?->nome ?? 'Membro não identificado';

        return [
            'type' => 'senha_ajuda',
            'title' => 'Pedido de ajuda no acesso',
            'message' => $this->message
                ? "{$nome} pediu ajuda para acessar a conta. E-mail informado: {$this->email}. Observação: {$this->message}"
                : "{$nome} pediu ajuda para acessar a conta. E-mail informado: {$this->email}.",
            'membro_id' => $this->membro?->id,
            'email' => $this->email,
        ];
    }
}
