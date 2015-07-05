<?php

use App\Item;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
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
        Item::truncate();

        foreach(range(1, 3) as $index)
        {
            $parent = $this->createItem();

            if ($index !== 3) {
                $this->createDescendants($parent);
            }
        }

    }

    public function createDescendants($parent)
    {
        foreach(range(1, 3) as $index)
        {
            $child1 = $this->createItem($parent);

            foreach(range(1, 3) as $index)
            {
                $child2 = $this->createItem($child1);

                foreach(range(1, 3) as $index)
                {
                    $child3 = $this->createItem($child2);

                    foreach(range(1, 3) as $index)
                    {
                        $child4 = $this->createItem($child3);
                    }
                }
            }
        }
    }

    /**
     *
     * @return Item
     */
    public function createItem($parent = NULL)
    {
        $item = new Item([
            'title' => $this->faker->sentence
        ]);

        $item->user()->associate(User::first());

        if(!is_null($parent))
        {
            $item->parent()->associate($parent);
        }

        $item->save();

        return $item;
    }
}
