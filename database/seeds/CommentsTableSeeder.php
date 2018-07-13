<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Article;
use App\Models\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $comments = [
            ['comment' => 'good'],
            ['comment' => 'very good'],
            ['comment' => 'great'],
            ['comment' => 'the article very useful'],
            ['comment' => 'Very nice'],
        ];

            foreach(range(1,20) as $index) {
                foreach ($comments as $comment) {
                    Comment::create([
                        'comment' => $faker->randomElement($comment),
                        'user_id' => $faker->numberBetween(1,11),
                        'article_id' => $faker->numberBetween(1,20)
                    ]);
                }


        }

    }
}
