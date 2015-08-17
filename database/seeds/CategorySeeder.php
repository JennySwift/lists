<?php

use App\Models\Category;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     *
     */
    public function __construct()
    {
        $this->faker = Faker::create();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::truncate();

        foreach(range(1, 3) as $index)
        {
            $category = new Category(['name' => $this->faker->word]);
            $category->user()->associate(User::first());
            $category->save();
        }

    }

}
