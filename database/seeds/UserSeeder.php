<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $jenny = User::create([
            'name' => 'Jenny',
            'email' => 'cheezyspaghetti@gmail.com',
            'password' => bcrypt('abcdefg'),
            'last_route' => '/items/16'
        ]);

        $dummy = User::create([
            'name' => 'Dummy',
            'email' => 'dummy@example.com',
            'password' => bcrypt('abcdefg'),
            'last_route' => '/items'
        ]);
    }
}
