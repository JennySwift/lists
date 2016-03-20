<?php

use App\Models\Category;
use App\Models\Item;
use App\User;
use Carbon\Carbon;
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

        $this->createItems();

        $this->deleteSomeItems();
        $this->pinSomeItems();
        $this->favouriteSomeItems();
        $this->makeSomeItemsUrgent();
        $this->giveAlarmsToSomeItems();
        $this->giveANotBeforeValueToSomeItems();

    }

    /**
     *
     */
    private function createItems()
    {
        foreach(range(1, 8) as $index)
        {
            $parent = $this->createItem();

            if ($index !== 3) {
                $this->createDescendants($parent);
            }
        }
    }

    /**
     *
     */
    private function giveANotBeforeValueToSomeItems()
    {
        $items = Item::orderBy('id', 'desc')->whereNull('parent_id')->limit(2)->get();
        foreach ($items as $item) {
            $item->not_before = Carbon::tomorrow()->format('Y-m-d H:i:s');
            $item->save();
        }
    }

    /**
     *
     */
    private function giveAlarmsToSomeItems()
    {
        $item = Item::whereNull('parent_id')->first();
        $item->alarm = Carbon::today()->hour(20)->minute(0)->second(30);
        $item->save();
    }

    /**
     *
     */
    private function makeSomeItemsUrgent()
    {
        //Give some items on the home page an urgency of 1
        $items = Item::whereNull('parent_id')->limit(2)->get();
        foreach ($items as $item) {
            $item->urgency = 1;
            $item->save();
        }

        //Give some items on the home page an urgency of 2
        $items = Item::whereNull('parent_id')->limit(2)->offset(2)->get();
        foreach ($items as $item) {
            $item->urgency = 2;
            $item->save();
        }

        //Give some items on the home page an urgency of 3
        $items = Item::whereNull('parent_id')->limit(2)->offset(4)->get();
        foreach ($items as $item) {
            $item->urgency = 3;
            $item->save();
        }
    }

    /**
     * Delete some items
     * This broke my tests, because I then had children existing
     * whose parents were deleted, so when I tried to update the child,
     * it errored saying that parent didn't exist.
     */
    private function deleteSomeItems()
    {
        $items = Item::orderBy('id', 'desc')->limit(4)->get();
        foreach ($items as $item) {
            $item->delete();
        }
    }

    /**
     *
     */
    private function pinSomeItems()
    {
        $items = Item::limit(4)->get();
        foreach ($items as $item) {
            $item->pinned = 1;
            $item->save();
        }
    }

    /**
     *
     */
    private function favouriteSomeItems()
    {
        $items = Item::limit(6)->offset(4)->get();
        foreach ($items as $item) {
            $item->favourite = 1;
            $item->save();
        }
    }

    /**
     *
     * @param $parent
     */
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

        if ($item->priority === 1) {
            $urgent = $this->faker->boolean(5);
            if ($urgent) {
                $item->urgency = 1;
            }
        }

        $item->user()->associate(User::first());

        if ($this->faker->boolean(50)) {
            $item->body = 'item body';
        }

        if(!is_null($parent))
        {
            $item->parent()->associate($parent);
        }

        $item->save();

        return $item;
    }

    /**
     *
     * @param $item
     * @return int
     */
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
