<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Tag;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1,10) as $index) {
            Tag::create([
                'name' => $faker->sentence($nbWords = rand(1,2)),
                'slug' => $faker->slug($nbWords=rand(1,2)),
            ]);
        }
    }
}
