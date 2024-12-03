<?php

namespace Database\Seeders;

use App\Models\Concession;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class OrderConcessionTableSeeder extends Seeder
{
    public function run()
    {
        $orders = Order::all();
        $concessions = Concession::all();

        foreach ($orders as $order) {
            foreach ($concessions as $concession) {
                $order->concessions()->attach($concession->id, [
                    'quantity' => rand(1, 5),
                    'price' => $concession->price,
                ]);
            }
        }
    }
}
