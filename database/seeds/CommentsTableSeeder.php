<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Comment;
use App\Models\User;
use App\Models\Post;

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
            $user_id = User::all()->pluck('id')->toArray();
            $first_user_id = array_first($user_id);
            $last_user_id = array_last($user_id);

            $post_id = Post::all()->pluck('id')->toArray();
            $first_post_id = array_first($post_id);
            $last_post_id = array_last($post_id);

            foreach ($comments as $comment) {
                Comment::create([
                    'comment' => $faker->randomElement($comment),
                    'user_id' => rand($first_user_id, $last_user_id),
                    'post_id' => rand($first_post_id, $last_post_id)
                ]);
            }

    }
}
