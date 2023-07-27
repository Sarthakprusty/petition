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

class GeneratePdfEventFwd implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $postParameter;
    public $application;
    public $name;
    public $name_hin;

    /**
     * Create a new event instance.
     *
     * @param array $postParameter
     * @param \App\Models\Application $application
     * @param string $name
     * @param string $name_hin
     */
    public function __construct($postParameter, $application,$name,$name_hin)
    {
        $this->postParameter = $postParameter;
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
