<?php

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Category;

class CategoryPostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Post::all();
        Category::all()->each(function ($categories) use ($posts) {
            $categories->posts()->attach(
                $posts->random(rand(1, 5))->pluck('id')
            );
        });
    }
}
