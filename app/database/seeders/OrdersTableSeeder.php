<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 5) as $index) {
            $user = User::inRandomOrder()->first();

            Order::create([
                'user_id' => $user->id,
                'status' => $faker->randomElement(['Pending', 'In Progress']),
                'send_to_kitchen_time' => Carbon::now()->addMinutes(rand(1, 120)),
                'total_cost' => $faker->randomFloat(2, 10, 100),
            ]);
        }
    }
}
