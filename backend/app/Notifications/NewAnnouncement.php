<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAnnouncement extends Notification implements ShouldQueue
{
    use Queueable;

    public $announcement;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'announcement' => $this->announcement,
            'timestamp' => now()
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'data' => [
                'announcement' => $this->announcement,
                'timestamp' => now()
            ]
        ];
    }
}
