<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeCourseStatus extends Notification implements ShouldQueue
{
    use Queueable;

    public $course;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($course)
    {
        $this->course = $course;
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
            'course' => [
                'id' => $this->course->id,
                'title' => $this->course->title,
                'thumbnail_url' => $this->course->thumbnail_url,
                'status' => $this->course->status
            ],
            'timestamp' => now()
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'data' => [
                'course' => [
                    'id' => $this->course->id,
                    'title' => $this->course->title,
                    'thumbnail_url' => $this->course->thumbnail_url,
                    'status' => $this->course->status
                ],
                'timestamp' => now()
            ]
        ];
    }
}
