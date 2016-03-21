<?php

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ItemsUpdateTest
 */
class ItemsUpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     *
     * @test
     * @return void
     */
    public function it_can_update_an_item()
    {
        DB::beginTransaction();
        $this->logInUser();
        $alarm = Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s');

        $item = Item::forCurrentUser()
            ->where('favourite', 0)
            ->where('pinned', 0)
            ->where('category_id', 1)
            ->where('priority', 1)
            ->whereNull('urgency')
            ->first();

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'title' => 'numbat',
            'body' => 'koala',
            'priority' => 2,
            'urgency' => 1,
            'favourite' => 1,
            'pinned' => 1,
            'parent_id' => 5,
            'category_id' => 2,
            'alarm' => $alarm,
            'not_before' => '2050-02-03 13:30:05',
            'recurring_unit' => 'hours',
            'recurring_frequency' => 6
        ]);

//        dd($response);
        $content = json_decode($response->getContent(), true);
        //dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals('numbat', $content['title']);
        $this->assertEquals('koala', $content['body']);
        $this->assertEquals(2, $content['priority']);
        $this->assertEquals(1, $content['favourite']);
        $this->assertEquals(1, $content['pinned']);
        $this->assertEquals(5, $content['parent_id']);
        $this->assertEquals($alarm, $content['alarm']);
        $this->assertEquals('2050-02-03 13:30:05', $content['notBefore']);
        $this->assertEquals('hours', $content['recurringUnit']);
        $this->assertEquals(6, $content['recurringFrequency']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     *
     * @test
     * @return void
     */
    public function it_can_remove_an_alarm_from_an_item()
    {
        DB::beginTransaction();
        $this->logInUser();

        $item = Item::forCurrentUser()
            ->whereNotNull('alarm')
            ->first();

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'alarm' => false
        ]);

        $content = json_decode($response->getContent(), true);
        //dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals(null, $content['alarm']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     *
     * @test
     * @return void
     */
    public function it_can_remove_an_urgency_from_an_item()
    {
        DB::beginTransaction();
        $this->logInUser();

        $item = Item::forCurrentUser()
            ->where('urgency', 1)
            ->first();

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'urgency' => false
        ]);

        $content = json_decode($response->getContent(), true);
        //dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals(null, $content['urgency']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     *
     * @test
     * @return void
     */
    public function it_can_unpin_an_item()
    {
        DB::beginTransaction();
        $this->logInUser();

        $item = Item::forCurrentUser()
            ->where('pinned', 1)
            ->first();

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'pinned' => 0,
            'favourite' => 0
        ]);

//        dd($response);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals(0, $content['pinned']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }


}