<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subject;

    public function __construct(string $subject)
    {
        $this->subject = $subject;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('External Request Completed: ' . $this->subject)
            ->greeting("Hello {$notifiable->name},")
            ->line('The external request ' . $this->subject . ' has been marked as completed.');
    }
}