<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PersonalAccessTokenCreated extends Notification implements ShouldQueue
{
    use Queueable;
    public $tries = 3;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $plainTextToken)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Personal Access Token added : ' . config('app.name'))
            ->line('Tu viens juste de créer un nouvel access token sur ' . config('app.name') . ' !')
            ->line('Voici son token : ' . $this->plainTextToken);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
