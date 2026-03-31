<?php

namespace App\Notifications\External;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyAssignee extends Notification implements ShouldQueue
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
        $this->url = url("/externals/mytasks/{$this->external->id}/verify");
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("DocTrax: New Task")
            ->greeting("Hello {$notifiable->name},")
            ->line("An external request has been assigned to you for your action.")
            ->line("**Subject:** {$this->external->subject}")
            ->line("**Reference:** {$this->external->reference}")
            ->line("**Received by:** {$this->external->creator->name}")
            ->action('View Request', $this->url)
            ->line('Thank you for using DocTrax!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'request_id' => $this->external->id,
            'subject' => 'New Assignment',
            'created_at' => now(),
            'created_by' => $this->creator_id,
            'url' => $this->url,
        ];
    }
}