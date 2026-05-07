<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class MessageToMember extends Notification
{
    use Queueable;

    protected $subject;
    protected $message;
    protected $fromUser;

    public function __construct(string $subject, string $message, $fromUser = null)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->fromUser = $fromUser;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->subject,
            'message' => $this->message,
            'from' => $this->fromUser ? ($this->fromUser->name ?? $this->fromUser->nome ?? 'Admin') : 'Admin',
        ];
    }
}
