<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class MessageSent implements ShouldBroadcast
{


    public function __construct(
        public string $user,
        public string $message,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('group-forum'),
        ];
    }
}
