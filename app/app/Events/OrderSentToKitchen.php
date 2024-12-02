<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderSentToKitchen
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('kitchen-orders'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->order->status,
            'send_to_kitchen_time' => $this->order->send_to_kitchen_time,
        ];
    }
}
