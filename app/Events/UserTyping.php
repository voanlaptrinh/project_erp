<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $groupId;
    public $isTyping;

    public function __construct($userId, $groupId, $isTyping)
    {
        $this->userId = $userId;
        $this->groupId = $groupId;
        $this->isTyping = $isTyping;
    }

    public function broadcastOn()
    {
        return new Channel('chat.'.$this->groupId);
    }

    public function broadcastAs()
    {
        return 'user-typing';
    }
}