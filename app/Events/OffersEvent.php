<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OffersEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $message;

    public function __construct($message)
    {
        $this->message = [
            'lawyer_id'         => $message->lawyer_id,
            'content'         => __('dashboard.new_offer'),
            'id'         => $message->offerable_id,
            'type'         => $message->offerable_type == "App\Cause" ? 0 : 1,
            'date'          => $message->created_at->format('h:i A'),
        ];
    }

    public function broadcastOn()
    {
        return ['my-channel'];
    }

    public function broadcastAs()
    {
        return 'my-event';
    }
}
