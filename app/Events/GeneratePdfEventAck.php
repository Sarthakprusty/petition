<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GeneratePdfEventAck implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $postParameter;
    public $application;

    /**
     * Create a new event instance.
     *
     * @param array $postParameter
     * @param \App\Models\Application $application
     */
    public function __construct($postParameter, $application)
    {
        $this->postParameter = $postParameter;
        $this->application = $application;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
