<?php

namespace App\Notifications\External;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected \App\Models\External $external;
    protected bool $sendMail;
    protected string $url;
    protected int $creator_id;

    public function __construct(\App\Models\External $external, bool $sendMail = true, int $creator_id = null)
    {
        $this->external = $external;
        $this->sendMail = $sendMail;
        $this->creator_id = $creator_id;
        $this->url = url("/externals/monitoring/{$this->external->id}");
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("DocTrax: Task Assigned")
            ->greeting("Hello {$notifiable->name},")
            ->line("An external request you are monitoring has been assigned.")
            ->line("**Subject:** {$this->external->subject}")
            ->line("**Reference:** {$this->external->reference}")
            ->line("**Received by:** {$this->external->creator->name}")
            ->line("**Assigned to:** {$this->external->assignedTo->name}")
            ->action('View Request', $this->url)
            ->line('Thank you for using DocTrax!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'request_id' => $this->external->id,
            'subject' => 'Pending Acceptance',
            'created_by' => $this->creator_id,
            'message' => 'A request you are monitoring has been assigned.',
            'url' => $this->url,
        ];
    }
}