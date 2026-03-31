<?php

namespace App\Notifications\External;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyCompleted extends Notification implements ShouldQueue
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
        $this->url = url("/externals/completed/{$this->external->id}");
    }

    public function via($notifiable): array
    {
        return $this->sendMail ? ['database', 'mail'] : ['database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("DocTrax: Request Completed - {$this->external->subject}")
            ->greeting("Hello {$notifiable->name},")
            ->line("A request you are monitoring has been completed.")
            ->line("**Subject:** {$this->external->subject}")
            ->line("**Reference:** {$this->external->reference}")
            ->action('View Completed Request', $this->url)
            ->line('Thank you for using DocTrax!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'request_id' => $this->external->id,
            'subject' => 'Task Completed',
            'created_at' => now(),
            'created_by' => $this->creator_id,
            'url' => $this->url,
        ];
    }
}