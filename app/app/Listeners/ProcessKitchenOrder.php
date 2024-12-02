<?php

namespace App\Listeners;

use App\Events\OrderSentToKitchen;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ProcessKitchenOrder
{
    public function __construct()
    {
    }

    public function handle(OrderSentToKitchen $event): void
    {
        $order = $event->order;
        Log::info("Order {$order->id} sent to kitchen at {$order->send_to_kitchen_time}.");
    }
}
