<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('DocTrax Account Created')
            ->greeting("Hello {$notifiable->name},")
            ->line('Your DocTrax account has been created.')
            ->line('Please use the following credentials to log in:')
            ->line("Email: {$notifiable->email}")
            ->line("Password: {$this->password}") 
            ->line('Alternatively, you can login using your GovMail account.')
            ->line('Thank you for using DocTrax!');
    }
}