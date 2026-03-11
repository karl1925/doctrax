<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAssignment extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subject;
    protected $reference;
    protected $remarks;

    public function __construct(string $subject, string $reference, string $remarks)
    {
        $this->subject = $subject;
        $this->reference = $reference;
        $this->remarks = $remarks;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Assignment Pending Acceptance')
            ->greeting("Hello {$notifiable->name},")
            ->line('A new external request has been assigned to you.')
            ->line("Subject: {$this->subject}")
            ->line("Reference: {$this->reference}")
            ->line("Remarks: {$this->remarks}")
            ->line('Should you accept this assignment, please log in to DocTrax to review the request and take necessary actions.')
            ->line('Thank you for using DocTrax!');
    }
}