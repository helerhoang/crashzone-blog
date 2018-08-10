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
            ['name' => 'Sports Car', 'parent_id' => null, 'slug' => 'sport-car'],
            ['name' => 'Van', 'parent_id' => null, 'slug' => 'van'],
            ['name' => 'Medium car', 'parent_id' => null, 'slug' => 'medium-car'],
            ['name' => 'Estimate', 'parent_id' => null, 'slug' => 'estimate'],
            ['name' => 'People Mover', 'parent_id' => null, 'slug' => 'people-mover'],

        ];
        foreach ($categories as $category) {
            Category::create($category);
        }

    }
}
