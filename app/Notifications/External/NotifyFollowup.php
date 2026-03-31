<?php

namespace App\Notifications\External;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyFollowup extends Notification implements ShouldQueue
{
    use Queueable;

    protected \App\Models\External $external;
    protected bool $sendMail;
    protected string $url;
    protected string $remarks;
    protected int $creator_id;

    public function __construct(\App\Models\External $external, bool $sendMail = true, string $remarks = "", int $creator_id)
    {
        $this->external = $external;
        $this->sendMail = $sendMail;
        $this->url = url("/externals/mytasks/{$this->external->id}/verify");
        $this->remarks = $remarks;
        $this->creator_id = $creator_id;
    }

    public function via($notifiable): array
    {
        return $this->sendMail ? ['database', 'mail'] : ['database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("DocTrax: Follow-Up on External Request")
            ->greeting("Hello {$notifiable->name},")
            ->line("An external request forwarded to you for follow-up action.")
            ->line("**Subject:** {$this->external->subject}")
            ->line("**Reference:** {$this->external->reference}")
            ->line("**Received by:** {$this->external->creator->name}")
            ->line("**Remarks:** {$this->remarks}")
            ->action('View Request', $this->url)
            ->line('Thank you for using DocTrax!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'request_id' => $this->external->id,    
            'subject' => 'Follow-Up',
            'created_at' => now(),
            'created_by' => $this->creator_id,
            'message' => $this->remarks ? "{$this->remarks}" : "You have a follow-up action on this request.",
            'url' => $this->url,
        ];
    }
}