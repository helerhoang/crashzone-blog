<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
             RolesTableSeeder::class,
             UsersTableSeeder::class,
            CategoriesTableSeeder::class,
            PostsTableSeeder::class,
            CommentsTableSeeder::class,
            ImagesTableSeeder::class,
            MenusTableSeeder::class,
            TagsTableSeeder::class,
            RoleUserTableSeeder::class,
            CategoryPostTableSeeder::class,
            ImagePostTableSeeder::class,
             PostTagTableSeeder::class
        ]);
    }
}
