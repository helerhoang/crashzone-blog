<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Article;
use App\Models\Category;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(0, 20) as $item) {
            Article::create([
                'title' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'title_seo' => 'some-title-seo-' . $item,
                'description' => $faker->paragraph(10),
                'content' => $faker->paragraph(20),
                'image' => $faker->imageUrl($width = 640, $height = 480)
            ]);
        }
        $articles = Article::all();
        Category::find(range(6, 10))->each(function ($categories) use ($articles) {
            $categories->articles()->attach(
                $articles->random(rand(1, 6))->pluck('id')->toArray()
            );
        });

    }
}
