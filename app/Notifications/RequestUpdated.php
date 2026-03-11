<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subject;
    protected $remarks;

    public function __construct(string $subject, string $remarks)
    {
        $this->subject = $subject;
        $this->remarks = $remarks;
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
            ->line('An external request has been updated with new remarks.')
            ->line("Remarks: {$this->remarks}");
    }
}