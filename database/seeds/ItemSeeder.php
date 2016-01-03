<?php

use App\Models\Category;
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

        //Pin some items
        $items = Item::limit(4)->get();
        foreach ($items as $item) {
            $item->pinned = 1;
            $item->save();
        }

        //Favourite some items
        $items = Item::orderBy('id', 'desc')->limit(6)->get();
        foreach ($items as $item) {
            $item->favourite = 1;
            $item->save();
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
        $categoryIds = Category::lists('id')->all();

        $item = new Item([
            'title' => $this->faker->sentence,
            'category_id' => $this->faker->randomElement($categoryIds),
            'priority' => $this->faker->numberBetween(1,5)
        ]);

        $item->user()->associate(User::first());

        if ($this->faker->boolean(50)) {
            $item->body = 'item body';
        }

        if(!is_null($parent))
        {
            $item->parent()->associate($parent);
        }

        $item->save();

//        $item->index = $this->getIndex($item);
//
//        $item->save();

        return $item;
    }

    private function getIndex($item)
    {
        //This is erroring because siblings method uses Auth. Not sure why it was working before.
        if ($item->lastSibling()) {
            $num = $item->lastSibling()->index;
            $num+=1;
        }
        else {
            $num = 0;
        }

        return $num;
    }
}
