<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestForForwarding extends Notification implements ShouldQueue
{
    use Queueable;

    protected $requestId;

    public function __construct(int $requestId)
    {
        $this->requestId = $requestId;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Request for recording and Forwarding to ORD')
            ->greeting("Hello {$notifiable->name},")
            ->line('A new request requires your attention.')
            ->action('View Request', url("/externals/recording/{$this->requestId}/verify"))
            ->line('Please review and forward to ORD it as soon as possible.');
    }
}