<?php

use Illuminate\Database\Seeder;
use App\Models\Article;
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
        $articles = Article::all();
        Category::find(range(6, 10))->each(function ($categories) use ($articles) {
            $categories->articles()->attach(
                $articles->random(rand(1, 5))->pluck('id')
            );
        });
    }
}
