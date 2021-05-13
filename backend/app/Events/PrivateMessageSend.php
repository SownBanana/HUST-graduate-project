<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrivateMessageSend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    private $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        // dump($this->data);
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.PrivateMessage.'.$this->data['id']);
        // return new PresenceChannel('App.PrivateMessage.'.$this->data['to']);
        // return new Channel('App.Global');
    }
    
    public function broadcastWith()
    {
        return [
            'data' => $this->data,
        ];
    }
}
