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

    private $items = [
        [
            'title' => '1',
            'category_id' => 1,
            'priority' => 1,
            'children' => [
                [
                    'title' => '1.1',
                    'category_id' => 1,
                    'priority' => 1,
                    'children' => [
                        [
                            'title' => '1.1.1',
                            'category_id' => 1,
                            'priority' => 1,
                        ],
                        [
                            'title' => '1.1.2',
                            'category_id' => 1,
                            'priority' => 1,
                        ],
                        [
                            'title' => '1.1.3',
                            'category_id' => 1,
                            'priority' => 1,
                        ]
                    ]
                ],
                [
                    'title' => '1.2',
                    'category_id' => 1,
                    'priority' => 1,
                ],
                [
                    'title' => '1.3',
                    'category_id' => 1,
                    'priority' => 1,
                    'body' => 'I am a note.'
                ],
                [
                    'title' => '1.4',
                    'category_id' => 1,
                    'priority' => 1,
                ],
                [
                    'title' => '1.5',
                    'category_id' => 1,
                    'priority' => 1,
                ],
                [
                    'title' => '1.6',
                    'category_id' => 2,
                    'priority' => 1,
                ],
                [
                    'title' => '1.7',
                    'category_id' => 1,
                    'priority' => 1,
                ],
                [
                    'title' => '1.8',
                    'category_id' => 2,
                    'priority' => 1,
                ],
                [
                    'title' => '1.9',
                    'category_id' => 1,
                    'priority' => 1,
                ],
            ]
        ],
        [
            'title' => '2',
            'category_id' => 1,
            'priority' => 1,
            'children' => [
                [
                    'title' => '2.1',
                    'category_id' => 1,
                    'priority' => 1,
                ],
            ]
        ],
        [
            'title' => '3',
            'category_id' => 1,
            'priority' => 1
        ],
        [
            'title' => 'Go for a walk',
            'category_id' => 4,
            'priority' => 2
        ],
        [
            'title' => 'Do some pushups',
            'category_id' => 4,
            'priority' => 1,
            'body' => 'How good are pushups. :)'
        ],
        [
            'title' => 'Do some pullups',
            'category_id' => 4,
            'priority' => 1,
            'favourite' => 1,
            'children' => [
                [
                    'title' => 'This item is very important',
                    'category_id' => 4,
                    'priority' => -1,
                ]
            ]
        ],
        [
            'title' => 'Throw stuff out',
            'category_id' => 5,
            'priority' => 2
        ],
        [
            'title' => 'Add new feature',
            'category_id' => 1,
            'priority' => 2,
            'favourite' => 1
        ],
        [
            'title' => 'Throw more stuff out',
            'category_id' => 5,
            'priority' => 2
        ],
        [
            'title' => 'Clean up',
            'category_id' => 5,
            'priority' => 2
        ],
        [
            'title' => 'Fix bug',
            'category_id' => 1,
            'priority' => 2
        ],
        [
            'title' => 'Do something whenever',
            'category_id' => 2,
            'priority' => 3
        ],
        [
            'title' => 'Do something today',
            'category_id' => 2,
            'priority' => 3,
            'days_ago' => 0
        ],
        [
            'title' => 'Do something yesterday',
            'category_id' => 2,
            'priority' => 3,
            'days_ago' => 1,
            'children' => [
                [
                    'title' => 'A completed child',
                    'category_id' => 2,
                    'priority' => 3,
                    'deleted' => 1
                ],
            ]
        ],
        [
            'title' => 'Do something tomorrow',
            'category_id' => 2,
            'priority' => 3,
            'days_ago' => -1
        ],
        [
            'title' => 'This item has a title that is longer than the items that have a shorter title.',
            'category_id' => 2,
            'priority' => 1,
        ],
        [
            'title' => 'A completed item',
            'category_id' => 2,
            'priority' => 4,
            'deleted' => 1,
            'children' => [
                [
                    'title' => 'A child of a completed item',
                    'category_id' => 2,
                    'priority' => 4,
                    'deleted' => 1
                ],
            ]
        ],
        [
            'title' => 'Another completed item',
            'category_id' => 2,
            'priority' => 4,
            'deleted' => 1
        ],
        [
            'title' => 'A repeating item',
            'category_id' => 2,
            'priority' => 1,
            'days_ago' => 0,
            'recurring_unit' => 'hour',
            'recurring_frequency' => 12
        ],
    ];

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

//            $this->deleteSomeItems();
//        $this->pinSomeItems();
//            $this->favouriteSomeItems();
//        $this->makeSomeItemsUrgent();
//        $this->giveAlarmsToSomeItems();


            $this->createControlledItems();

            $this->createRandomItems();
            $this->giveANotBeforeValueToSomeItems();
        }
    }

    /**
     *
     */
    private function createRandomItems()
    {
        foreach(range(1, 8) as $index)
        {
            $parent = $this->createRandomItem();

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
        foreach($this->items as $item)
        {
            $newItem = $this->createItem($item);

//            if(!is_null($item['parent']))
//            {
//                $newItem->parent()->associate($item['parent']);
//            }
        }

    }

    /**
     *
     * @param $item
     * @param $parent
     * @return Item
     */
    private function createItem($item, $parent = NULL)
    {
        $newItem = new Item([
            'title' => $item['title'],
            'category_id' => $item['category_id'],
            'priority' => $item['priority']
        ]);

        if (isset($item['days_ago'])) {
            $newItem['not_before'] = Carbon::today()->subDays($item['days_ago'])->format('Y-m-d H:i:s');
        }

        if (isset($item['recurring_unit'])) {
            $newItem['recurring_unit'] = $item['recurring_unit'];
        }

        if (isset($item['recurring_frequency'])) {
            $newItem['recurring_frequency'] = $item['recurring_frequency'];
        }

        if (isset($item['favourite'])) {
            $newItem['favourite'] = 1;
        }

        if (isset($item['body'])) {
            $newItem['body'] = $item['body'];
        }

        $newItem->user()->associate($this->user);

        if (isset($parent)) {
            $newItem->parent()->associate($parent);
        }

        $newItem->save();

        if (isset($item['children'])) {
            foreach ($item['children'] as $child) {
                $this->createItem($child, $newItem);
            }
        }

        if (isset($item['deleted'])) {
            $newItem->delete();
        }

        return $newItem;
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
     * Todo: delete some items that don't have children
     * Delete some items
     * This broke my tests, because I then had children existing
     * whose parents were deleted, so when I tried to update the child,
     * it errored saying that parent didn't exist.
     */
//    private function deleteSomeItems()
//    {
//        $items = Item::where('user_id', $this->user->id)->orderBy('id', 'desc')->limit(4)->get();
//        foreach ($items as $item) {
//            $item->delete();
//        }
//
//        //Delete some from the top level, too
//        $items = Item::where('user_id', $this->user->id)->where('parent_id', null)->limit(2)->get();
//
//        foreach ($items as $item) {
//            $item->delete();
//        }
//    }

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
//    private function favouriteSomeItems()
//    {
//        $items = Item::where('user_id', $this->user->id)->limit(18)->offset(4)->get();
//        foreach ($items as $item) {
//            $item->favourite = 1;
//            $item->save();
//        }
//    }

    /**
     *
     * @param $parent
     */
    public function createDescendants($parent)
    {
        foreach(range(1, 3) as $index)
        {
            $child1 = $this->createRandomItem($parent);

            foreach(range(1, 3) as $index)
            {
                $child2 = $this->createRandomItem($child1);

                foreach(range(1, 3) as $index)
                {
                    $child3 = $this->createRandomItem($child2);

                    foreach(range(1, 3) as $index)
                    {
                        $child4 = $this->createRandomItem($child3);
                    }
                }
            }
        }
    }

    /**
     *
     * @param null $parent
     * @return Item
     */
    public function createRandomItem($parent = NULL)
    {
        $categoryIds = Category::where('user_id', $this->user->id)->pluck('id')->all();

        $item = new Item([
            'title' => $this->faker->sentence,
            'category_id' => $this->faker->randomElement($categoryIds),
            'priority' => $this->faker->numberBetween(4,7)
        ]);

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
