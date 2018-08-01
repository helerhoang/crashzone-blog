<?php

use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\Post;

class PostTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = Tag::all();

        Post::all()->each(function ($posts) use ($tags) {
            $posts->tags()->attach(
                $tags->random(rand(1,10))->pluck('id')->toArray()
            );
        });
    }
}
