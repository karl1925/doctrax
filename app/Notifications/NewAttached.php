<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAttached extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subject;
    protected string $attach;

    public function __construct(string $subject, string $attach)
    {
        $this->subject = $subject;
        $this->attach = $attach;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('External Request Updated: ' . $this->subject)
            ->greeting("Hello {$notifiable->name},")
            ->line('Attachments have been added to the external request.')
            ->line("Attachments: " . $this->attach);
    }
}