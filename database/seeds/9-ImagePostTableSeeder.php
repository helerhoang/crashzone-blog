<?php

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Image;

class ImagePostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images = Image::all();

        Post::all()->each(function ($post) use ($images) {
            $post->images()->attach(
                $images->random(rand(1, 5))->pluck('id')
            );
        });
    }
}
