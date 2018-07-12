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
            ['parent_id' => '2','name' => 'Sports Car'],
            ['parent_id' => '2','name' => 'Van'],
            ['parent_id' => '2','name' => 'Medium car'],
            ['parent_id' => '2','name' => 'Estimate'],
            ['parent_id' => '2','name' => 'People Mover'],

        ];
        foreach($categories as $category) {
            Category::create($category);
        }

    }
}
