<?php

use Illuminate\Database\Eloquent\Model;
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Model::unguard();

        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ItemSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Model::reguard();
    }
}
