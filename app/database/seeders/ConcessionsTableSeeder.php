<?php

namespace Database\Seeders;

use App\Models\Concession;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class ConcessionsTableSeeder extends Seeder
{

    public function run()
    {
        $faker = Faker::create();

        $images = [
            '001.jpg',
            '002.jpg',
            '003.jpg',
            '004.jpg',
            '005.jpg',
        ];

        foreach (range(1, 5) as $index) {
            Concession::create([
                'name' => $faker->word(),
                'description' => $faker->sentence(),
                'image' => 'uploads/images/concessions/' . $images[$index - 1],
                'price' => $faker->randomFloat(2, 5, 50),
            ]);
        }
    }
}
