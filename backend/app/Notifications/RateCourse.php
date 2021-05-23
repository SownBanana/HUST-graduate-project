<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RateCourse extends Notification
{
    use Queueable;

    public $user;
    public $course;
    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $course, $data)
    {
        $this->user = $user;
        $this->course = $course;
        $this->data = $data;
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
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar_url' => $this->user->avatar_url,
            ],
            'course' => [
                'id' => $this->course->id,
                'title' => $this->course->title,
                'thumbnail_url' => $this->course->thumbnail_url,
                'rate' => $this->data['rate'],
            ],
            'timestamp' => $this->data['timestamp']
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'data' => [
                'user' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'avatar_url' => $this->user->avatar_url,
                ],
                'course' => [
                    'id' => $this->course->id,
                    'title' => $this->course->title,
                    'thumbnail_url' => $this->course->thumbnail_url,
                    'rate' => $this->data['rate'],
                ],
                'timestamp' => $this->data['timestamp']
            ]
        ];
    }
}
