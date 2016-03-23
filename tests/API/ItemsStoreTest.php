<?php

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class ItemsStoreTest
 */
class ItemsStoreTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_create_an_item()
    {
        DB::beginTransaction();
        $this->logInUser();
        $alarm = Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s');

        $item = [
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
            'recurring_unit' => 'hour',
            'recurring_frequency' => 6
        ];

        $response = $this->call('POST', '/api/items', $item);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals('numbat', $content['title']);
        $this->assertEquals('koala', $content['body']);
        $this->assertEquals(2, $content['priority']);
        $this->assertEquals(1, $content['urgency']);
        $this->assertEquals(1, $content['favourite']);
        $this->assertEquals(1, $content['pinned']);
        $this->assertEquals(5, $content['parent_id']);
        $this->assertEquals(2, $content['category_id']);
        $this->assertEquals($alarm, $content['alarm']);
        $this->assertEquals('2050-02-03 13:30:05', $content['notBefore']);
        $this->assertEquals('hour', $content['recurringUnit']);
        $this->assertEquals(6, $content['recurringFrequency']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_create_an_item_with_a_null_recurring_unit()
    {
        DB::beginTransaction();
        $this->logInUser();
        $alarm = Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s');

        $item = [
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
            'recurring_unit' => 'none'
        ];

        $response = $this->call('POST', '/api/items', $item);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals('numbat', $content['title']);
        $this->assertEquals('koala', $content['body']);
        $this->assertEquals(2, $content['priority']);
        $this->assertEquals(1, $content['urgency']);
        $this->assertEquals(1, $content['favourite']);
        $this->assertEquals(1, $content['pinned']);
        $this->assertEquals(5, $content['parent_id']);
        $this->assertEquals(2, $content['category_id']);
        $this->assertEquals($alarm, $content['alarm']);
        $this->assertEquals('2050-02-03 13:30:05', $content['notBefore']);
        $this->assertNull($content['recurringUnit']);
        $this->assertNull($content['recurringFrequency']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * For my feedback feature. Since my app listens for my Pusher event when a user provides feedback,
     * if I had the app open twice, the item would get inserted twice.
     * @test
     * @return void
     */
    public function it_cannot_create_an_item_that_already_exists()
    {
        DB::beginTransaction();
        $this->logInUser();

        $item = [
            'title' => 'koala',
            'body' => 'kangaroo',
            'priority' => 1,
            'urgency' => 1,
            'favourite' => 1,
            'pinned' => 1,
            'parent_id' => 2,
            'category_id' => 3,
        ];

        $response = $this->call('POST', '/api/items', $item);
        $content = json_decode($response->getContent(), true);

        $duplicateItem = [
            'title' => 'koala',
            'body' => 'kangaroo',
            'parent_id' => 2,
            'category_id' => 3,
            'priority' => 1
        ];

        $response = $this->call('POST', '/api/items', $duplicateItem);
//        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_create_an_item_without_an_alarm()
    {
        DB::beginTransaction();
        $this->logInUser();
        $alarm = Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s');

        $item = [
            'title' => 'numbat',
            'body' => 'koala',
            'priority' => 2,
            'urgency' => 1,
            'favourite' => 1,
            'pinned' => 1,
            'parent_id' => 5,
            'category_id' => 2,
            'alarm' => false
        ];

        $response = $this->call('POST', '/api/items', $item);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals('numbat', $content['title']);
        $this->assertEquals('koala', $content['body']);
        $this->assertEquals(2, $content['priority']);
        $this->assertEquals(1, $content['urgency']);
        $this->assertEquals(1, $content['favourite']);
        $this->assertEquals(1, $content['pinned']);
        $this->assertEquals(5, $content['parent_id']);
        $this->assertEquals(2, $content['category_id']);
        $this->assertNull($content['alarm']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_create_an_unpinned_item()
    {
        DB::beginTransaction();
        $this->logInUser();

        $item = [
            'title' => 'numbat',
            'body' => 'koala',
            'priority' => 2,
            'urgency' => 1,
            'favourite' => 1,
            'pinned' => 0,
            'parent_id' => 5,
            'category_id' => 2
        ];

        $response = $this->call('POST', '/api/items', $item);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals('numbat', $content['title']);
        $this->assertEquals('koala', $content['body']);
        $this->assertEquals(2, $content['priority']);
        $this->assertEquals(1, $content['urgency']);
        $this->assertEquals(1, $content['favourite']);
        $this->assertEquals(0, $content['pinned']);
        $this->assertEquals(5, $content['parent_id']);
        $this->assertEquals(2, $content['category_id']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        DB::rollBack();
    }


    /**
     * @test
     * @return void
     */
    public function it_throws_an_exception_for_item_store_method_without_required_fields()
    {
        DB::beginTransaction();
        $this->logInUser();

        $item = [

        ];

        $response = $this->apiCall('POST', '/api/items', $item);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertArrayHasKey('title', $content);
        $this->assertArrayHasKey('priority', $content);
        $this->assertArrayHasKey('category_id', $content);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_throws_an_exception_for_item_store_method_if_title_is_too_long()
    {
        DB::beginTransaction();
        $this->logInUser();

        $item = [
            'title' => '
                abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
                abcdefgabcdefgabcdefgabcdefgabcdefg abcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefgabcdefg
            ',
            'priority' => 1,
            'category_id' => 2,
            'favourite' => 0,
            'pinned' => 0
        ];

        $response = $this->apiCall('POST', '/api/items', $item);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertArrayHasKey('title', $content);
        $this->assertEquals('The title may not be greater than 100 characters.', $content['title'][0]);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

        DB::rollBack();
    }

}