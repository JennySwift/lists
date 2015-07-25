<?php

use App\Models\Item;
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

//        if ($item->parent) {
//            var_dump($item->parent->id);
//        }
        $item->order_number = $this->getOrderNumber($item);

        $item->save();

        return $item;
    }

    private function getOrderNumber($item)
    {
//        if (!$item->lastSibling()) {
//            var_dump('none');
//            return 1;
//        }
//        dd($item->lastSibling()->order_number);
//        var_dump($item->lastSibling()->id);
//        if ($item->parent) {
//            var_dump($item->parent->id);
//        }

//        if ($item->lastSibling()) {
//            var_dump($item->lastSibling()->order_number);
//        }
//
//        return 4;
////        return 1;

        if ($item->lastSibling()) {
            $num = $item->lastSibling()->order_number;
            $num+=1;
            var_dump($item->lastSibling()->id);
        }
        else {
            $num = 1;
        }

        return $num;
    }
}
