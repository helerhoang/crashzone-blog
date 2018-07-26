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


        foreach (range(1, 5) as $index) {
            Image::create([
                'name' => faker()->sentence(6),
                'alt' => faker()->slug(),
                'slug' => faker()->slug(),
                // 'path' => faker()->image($dir = '/tmp', $width = 640, $height = 480),
                'path' => faker()->imageUrl($width = 640, $height = 480, 'cats', true, 'Faker'),
                'extension' => faker()->fileExtension,
                'mime_type' => faker()->mimeType,
                'size' => faker()->randomNumber($nbDigits = null, $strict = false)
            ]);
        }

    }
}
