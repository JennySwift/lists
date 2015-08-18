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

        $categoryNames = ['coding', 'social', 'faith', 'health', 'minimalism'];

        foreach($categoryNames as $name)
        {
            $category = new Category(['name' => $name]);
            $category->user()->associate(User::first());
            $category->save();
        }

    }

}
