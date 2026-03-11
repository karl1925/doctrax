<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestForEndorsement extends Notification implements ShouldQueue
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
            ->subject('Request for Endorsement')
            ->greeting("Hello {$notifiable->name},")
            ->line('A new request requires your endorsement.')
            ->action('View Request', url("/externals/endorsing/{$this->requestId}/verify"))
            ->line('Please review and endorse it as soon as possible.');
    }
}