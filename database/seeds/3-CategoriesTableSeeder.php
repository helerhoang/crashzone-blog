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
            ['name' => 'Sports Car'],
            ['name' => 'Van'],
            ['name' => 'Medium car'],
            ['name' => 'Estimate'],
            ['name' => 'People Mover'],

        ];
        foreach ($categories as $category) {
            Category::create($category);
        }

    }
}
