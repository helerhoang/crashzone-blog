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
            // RolesTableSeeder::class,
            // UsersTableSeeder::class,
            // CategoriesTableSeeder::class,
            // PostsTableSeeder::class,
        //     CommentsTableSeeder::class,
            // ImagesTableSeeder::class,
        //     RoleUserTableSeeder::class,
        //     ArticleCategoryTableSeeder::class,
            // ImagePostTableSeeder::class,
            // MenusTableSeeder::class,
            // TagsTableSeeder::class,
            // PostTagTableSeeder::class
        ]);
    }
}
