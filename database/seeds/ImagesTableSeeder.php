<?php

use Illuminate\Database\Seeder;
use App\Models\Image;
use App\Models\Article;
use Faker\Factory as Faker;

class ImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            Image::create([
                'name_image' => $faker->sentence(6),
                'name_image_seo' => 'image-name-seo-'. $index,
                'link' => $faker->imageUrl($width = 640, $height = 480)
            ]);
        }

    }
}
