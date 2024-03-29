<?php

namespace App\Events;

use Dflydev\DotAccessData\Data;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $id_canal;
    //public $id_estudiante;

    /**
     * Create a new event instance.
     *
     * @param String param_data
     */
    public function __construct($param_data, $param_id_canal)
    {
        $this->data          = $param_data;
        $this->id_canal     = $param_id_canal;
        //$this->id_estudiante = $param_for_user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('sync_chat_'. $this->id_canal),
        ];
    }
}
