<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendEmailEventFwd implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $application;
    public $name;
    public $name_hin;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Application $application
     * @param string $name
     * @param string $name_hin
     */
    public function __construct($application,$name,$name_hin)
    {
        $this->application = $application;
        $this->name = $name;
        $this->name_hin = $name_hin;

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
