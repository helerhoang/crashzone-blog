<?php

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            ['name' => 'Home', 'slug' => 'home'],
            ['name' => 'Blog', 'slug' => 'blog'],
            ['name' => 'Contact', 'slug' => 'contact'],
            ['name' => 'Forums', 'slug' => 'forums'],
            ['name' => 'Help', 'slug' => 'help'],


        ];
        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
