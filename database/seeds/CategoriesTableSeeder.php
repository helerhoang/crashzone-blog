<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['parent_id'=> null,'name' => 'Home'],
            ['parent_id'=> null,'name' => 'Blog'],
            ['parent_id'=> null,'name' => 'Contact Us'],
            ['parent_id'=> null,'name' => 'Forums'],
            ['parent_id'=> null,'name' => 'Help'],
        ];
        foreach($categories as $category) {
            Category::create($category);
        }

    }
}
