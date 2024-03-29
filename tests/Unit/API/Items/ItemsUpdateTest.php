<?php

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class ItemsUpdateTest
 */
class ItemsUpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Todo: When I made the recurring unit 'hours' instead of 'hour', it was 'hours' in the response. That shouldn't work because the column is type 'enum.'
     * @test
     * @return void
     */
    public function it_can_update_an_item()
    {
        DB::beginTransaction();
        $this->logInUser();
        $alarm = Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s');

        $this->restoreTrashedItems();

        $item = Item::forCurrentUser()
            ->where('favourite', 0)
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
            'parent_id' => 5,
            'category_id' => 2,
            'alarm' => $alarm,
            'not_before' => '2050-02-03 13:30:05',
            'recurring_unit' => 'hour',
            'recurring_frequency' => 6
        ]);

//        dd($response);
        $content = $this->getContent($response);
//        dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals('numbat', $content['title']);
        $this->assertEquals('koala', $content['body']);
        $this->assertEquals(2, $content['priority']);
        $this->assertEquals(1, $content['favourite']);
        $this->assertEquals(5, $content['parent_id']);
        $this->assertEquals($alarm, $content['alarm']);
        $this->assertEquals('2050-02-03 13:30:05', $content['notBefore']);
        $this->assertEquals('hour', $content['recurringUnit']);
        $this->assertEquals(6, $content['recurringFrequency']);

        $this->assertResponseOk($response);

        DB::rollBack();
    }

    /**
     * Todo: When I made the recurring unit 'hours' instead of 'hour', it was 'hours' in the response. That shouldn't work because the column is type 'enum.'
     * @test
     * @return void
     */
    public function it_can_update_the_item_with_id_35()
    {
        $this->logInUser();

        $item = Item::forCurrentUser()->find(35);

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'title' => 'numbat',
            'body' => 'koala',
            'priority' => 2,
            'urgency' => 1,
            'favourite' => 1,
            'parent_id' => null,
            'category_id' => 2,
            'not_before' => '2050-02-03 13:30:05',
            'recurring_unit' => 'hour',
            'recurring_frequency' => 6
        ]);

//        dd($response);
        $content = $this->getContent($response);
//        dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals('numbat', $content['title']);
        $this->assertEquals('koala', $content['body']);
        $this->assertEquals(2, $content['priority']);
        $this->assertEquals(1, $content['favourite']);
        $this->assertNull($content['parent_id']);
        $this->assertEquals('2050-02-03 13:30:05', $content['notBefore']);
        $this->assertEquals('hour', $content['recurringUnit']);
        $this->assertEquals(6, $content['recurringFrequency']);

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_the_parent_id_of_an_item_to_null()
    {
        DB::beginTransaction();
        $this->logInUser();
        $this->restoreTrashedItems();

        $item = Item::forCurrentUser()
            ->where('parent_id', 1)
            ->first();

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'parent_id' => 'none',
        ]);

        $content = $this->getContent($response);
//        dd($content);

        $this->checkItemKeysExist($content);

        $this->assertNull($content['parent_id']);

        $this->assertResponseOk($response);

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_the_not_before_time_of_an_item_to_null()
    {
        DB::beginTransaction();
        $this->logInUser();

        $item = Item::forCurrentUser()
            ->whereNotNull('not_before')
            ->first();

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'not_before' => null
        ]);

        $content = $this->getContent($response);
//        dd($content);

        $this->checkItemKeysExist($content);

        $this->assertNull($content['notBefore']);

        $this->assertResponseOk($response);

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

        $this->createAlarms();

        $item = Item::forCurrentUser()
            ->whereNotNull('alarm')
            ->first();

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'alarm' => false
        ]);

        $content = $this->getContent($response);
        //dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals(null, $content['alarm']);

        $this->assertResponseOk($response);

        DB::rollBack();
    }

    /**
     *
     * @test
     * @return void
     */
    public function it_can_remove_the_recurring_frequency_from_an_item()
    {
        DB::beginTransaction();
        $this->logInUser();

        $item = Item::forCurrentUser()
            ->whereNotNull('recurring_frequency')
            ->first();

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'recurring_frequency' => ''
        ]);

        $content = $this->getContent($response);
        //dd($content);

        $this->checkItemKeysExist($content);

        $this->assertNull($content['recurringFrequency']);

        $this->assertResponseOk($response);

        DB::rollBack();
    }

    /**
     *
     * @test
     * @return void
     */
    public function it_can_make_a_favourite_item_no_longer_a_favourite()
    {
        DB::beginTransaction();
        $this->logInUser();

        $item = Item::forCurrentUser()
            ->where('favourite', 1)
            ->first();

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'favourite' => 0
        ]);

        $content = $this->getContent($response);
        //dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals(0, $content['favourite']);
        $this->assertEquals($item->title, $content['title']);

        $this->assertResponseOk($response);

        DB::rollBack();
    }

    /**
     *
     * @test
     * @return void
     */
    public function it_can_set_the_recurring_unit_of_an_item_to_null()
    {
        DB::beginTransaction();
        $this->logInUser();

        $item = Item::forCurrentUser()
            ->whereNotNull('recurring_unit')
            ->first();

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'recurring_unit' => 'none'
        ]);

        $content = $this->getContent($response);
        //dd($content);

        $this->checkItemKeysExist($content);

        $this->assertNull($content['recurringUnit']);

        $this->assertResponseOk($response);

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

        $this->createUrgentItems();

        $item = Item::forCurrentUser()
            ->where('urgency', 1)
            ->first();

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'urgency' => false
        ]);

        $content = $this->getContent($response);
        //dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals(null, $content['urgency']);

        $this->assertResponseOk($response);

        DB::rollBack();
    }

    /**
     *
     * @test
     * @return void
     */
    public function it_can_remove_a_note_from_an_item()
    {
        DB::beginTransaction();
        $this->logInUser();

        $item = Item::forCurrentUser()
            ->whereNotNull('body')
            ->first();

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'body' => ''
        ]);

        $content = $this->getContent($response);
        //dd($content);

        $this->checkItemKeysExist($content);
        $this->assertEquals(null, $content['body']);


        $this->assertResponseOk($response);

        DB::rollBack();
    }

}