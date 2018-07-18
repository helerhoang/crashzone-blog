<?php

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Image;

class ArticleImageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articles = Article::all();

        Image::all()->each(function($images) use ($articles) {
            $images->articles()->attach(
                $articles->random(rand(1,20))->pluck('id')->toArray()
            );
        });
    }
}
