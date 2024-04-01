<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TutUpdateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $id_canal;

    /**
     * Create a new event instance.
     */
    public function __construct($param_data, $param_id_canal)
    {
        $this->data     = $param_data    ;
        $this->id_canal = $param_id_canal;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('tut_access_'.$this->id_canal),
        ];
    }
}
