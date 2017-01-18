<?php

use App\Models\Category;
use App\Models\Item;
use App\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    protected $user;

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

        $users = User::all();

        foreach ($users as $user) {
            $this->user = $user;

            $this->createItems();

            $this->deleteSomeItems();
//        $this->pinSomeItems();
            $this->favouriteSomeItems();
//        $this->makeSomeItemsUrgent();
//        $this->giveAlarmsToSomeItems();
            $this->giveANotBeforeValueToSomeItems();

            $this->createControlledItems();
        }
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
    public function createControlledItems()
    {
        //Create top level items
        $item1 = $this->createControlledItem('1');
        $item2 = $this->createControlledItem('2');
        $item3 = $this->createControlledItem('3');

        //Create child items
        $item1Child = $this->createControlledItem('1.1', $item1);
        $this->createControlledItem('1.2', $item1);
        $this->createControlledItem('1.3', $item1);

        //Create deeper items
        $this->createControlledItem('1.1.1', $item1Child);
        $this->createControlledItem('1.1.2', $item1Child);
        $this->createControlledItem('1.1.3', $item1Child);

        $this->createControlledItem('2.1', $item2);

    }

    /**
     *
     * @param $title
     * @param null $parent
     * @return Item
     */
    public function createControlledItem($title, $parent = NULL)
    {
        $item = new Item([
            'title' => $title,
            'category_id' => 1,
            'priority' => 1
        ]);

        $item->user()->associate($this->user);

        if(!is_null($parent))
        {
            $item->parent()->associate($parent);
        }

        $item->save();

        return $item;
    }

    /**
     *
     */
    private function giveANotBeforeValueToSomeItems()
    {
        $dateTime = Carbon::yesterday()->subDays(1);
        $items = Item::where('user_id', $this->user->id)->orderBy('id', 'desc')->whereNull('parent_id')->limit(3)->get();

        foreach ($items as $index => $item) {
            $dateTime->addDay(1);
            $item->not_before = $dateTime->copy()->format('Y-m-d H:i:s');

            if ($index === 2) {
                //Make it a recurring item
                $item->recurring_unit = 'minute';
                $item->recurring_frequency = 1;
            }

            $item->save();
        }
    }

    /**
     *
     */
    private function giveAlarmsToSomeItems()
    {
        $item = Item::where('user_id', $this->user->id)->whereNull('parent_id')->first();
        $item->alarm = Carbon::today()->hour(20)->minute(0)->second(30);
        $item->save();
    }

    /**
     *
     */
    private function makeSomeItemsUrgent()
    {
        //Give some items on the home page an urgency of 1
        $items = Item::where('user_id', $this->user->id)->whereNull('parent_id')->limit(2)->get();
        foreach ($items as $item) {
            $item->urgency = 1;
            $item->save();
        }

        //Give some items on the home page an urgency of 2
        $items = Item::where('user_id', $this->user->id)->whereNull('parent_id')->limit(2)->offset(2)->get();
        foreach ($items as $item) {
            $item->urgency = 2;
            $item->save();
        }

        //Give some items on the home page an urgency of 3
        $items = Item::where('user_id', $this->user->id)->whereNull('parent_id')->limit(2)->offset(4)->get();
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
        $items = Item::where('user_id', $this->user->id)->orderBy('id', 'desc')->limit(4)->get();
        foreach ($items as $item) {
            $item->delete();
        }

        //Delete some from the top level, too
        $items = Item::where('user_id', $this->user->id)->where('parent_id', null)->limit(2)->get();

        foreach ($items as $item) {
            $item->delete();
        }
    }

    /**
     *
     */
    private function pinSomeItems()
    {
        $items = Item::where('user_id', $this->user->id)->limit(4)->get();
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
        $items = Item::where('user_id', $this->user->id)->limit(18)->offset(4)->get();
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
        $categoryIds = Category::where('user_id', $this->user->id)->lists('id')->all();

        $item = new Item([
            'title' => $this->faker->sentence,
            'category_id' => $this->faker->randomElement($categoryIds),
            'priority' => $this->faker->numberBetween(1,5)
        ]);

//        if ($item->priority === 1) {
//            $urgent = $this->faker->boolean(5);
//            if ($urgent) {
//                $item->urgency = 1;
//            }
//        }

        $item->user()->associate($this->user);

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
