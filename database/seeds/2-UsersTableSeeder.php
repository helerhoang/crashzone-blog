<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = Faker::create();
        // User::create([
        //     'name' => 'nam',
        //     'email' => 'nam@autointegrity.com.au',
        //     'password' => bcrypt('123456')
        // ]);

        foreach (range(0, 10) as $index) {
            User::create([
                'name' => faker()->name,
                'email' => faker()->unique()->safeEmail,
                'password' => bcrypt('123456')
            ]);

        }

    }
}
