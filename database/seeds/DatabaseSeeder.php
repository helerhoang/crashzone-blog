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
            ArticlesTableSeeder::class,
            CommentsTableSeeder::class,
            ImagesTableSeeder::class,
            RoleUserTableSeeder::class,
            ArticleCategoryTableSeeder::class,
            ArticleImageTableSeeder::class,
            MenusTableSeeder::class,
        ]);
    }
}
