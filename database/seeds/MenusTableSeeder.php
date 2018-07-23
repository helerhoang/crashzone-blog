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
            ['name_menu' => 'Home'],
            ['name_menu' => 'Blog'],
            ['name_menu' => 'Contact'],
            ['name_menu' => 'Forums'],
            ['name_menu' => 'Help'],


        ];
        foreach($menus as $menu) {
            Menu::create($menu);
        }
    }
}
