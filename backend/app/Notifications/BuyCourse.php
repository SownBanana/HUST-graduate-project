<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BuyCourse extends Notification implements ShouldQueue
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
        return ['database', 'broadcast', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Khóa học của bạn vừa được mua');
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
                ],
                'timestamp' => $this->data['timestamp']
            ]
        ];
    }
}
