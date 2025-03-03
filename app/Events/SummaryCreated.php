<?php

namespace App\Events;

use App\Models\Summary;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SummaryCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $summary;
    public $receipt;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Summary $model, string $receipt)
    {
        //
        $this->summary = $model;
        $this->receipt = $receipt;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
