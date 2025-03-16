<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OddsUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $odds;
    /**
     * Create a new event instance.
     */
    public function __construct($odds)
    {
        $this->odds = $odds;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return new Channel('live-odds'); // Public channel
    }

    public function broadcastAs()
    {
        return 'OddsUpdatedEvent'; // Event name for frontend
    }
}
