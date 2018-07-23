<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;

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

        $users = User::all();
        $array_id = $users->pluck('id')->toArray();
        $first_id = array_first($array_id);
        $last_id = array_last($array_id);
        foreach (range(0, 20) as $item) {
            Article::create([
                'title' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'title_seo' => 'some-title-seo-' . $item,
                'user_id' => rand($first_id, $last_id),
                'description' => $faker->paragraph(10),
                'content' => $faker->randomHtml(4, 5),
            ]);
        }
    }
}
