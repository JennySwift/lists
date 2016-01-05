<?php

use App\Models\Item;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class ItemsTest
 */
class ItemsTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * @test
     * @return void
     */
    public function it_gets_the_items()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items');
        $content = json_decode($response->getContent(), true);
//      dd($content);
    
        $this->checkItemKeysExist($content[0]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_filter_the_items()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items?filter=au');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        foreach ($content as $item) {
            $this->assertContains('au', $item['title'], '', true);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_deleted_items()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items?trashed=true');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        foreach ($content as $item) {
            $this->assertArrayHasKey('deleted_at', $item);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_empty_the_trash()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items?trashed=true');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        foreach ($content as $item) {
            $this->assertArrayHasKey('deleted_at', $item);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_show_an_item()
    {
        $this->logInUser();

        $item = Item::forCurrentUser()->first();

        $response = $this->call('GET', '/api/items/' . $item->id);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkItemKeysExist($content);
        $this->checkItemKeysExist($content['children'][0]);
        $this->checkItemKeysExist($content['breadcrumb'][0]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_item_does_not_exist()
    {
        $this->logInUser();

        $response = $this->call('GET', '/api/items/5000');
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertEquals('Item not found.', $content['error']);
        $this->assertEquals(404, $content['status']);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_pinned_items()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items?pinned=true');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        foreach ($content as $item) {
            $this->assertEquals(1, $item['pinned']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_favourite_items()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items?favourites=true');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        foreach ($content as $item) {
            $this->assertEquals(1, $item['favourite']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_create_an_item()
    {
        DB::beginTransaction();
        $this->logInUser();

        $item = [
            'title' => 'numbat',
            'body' => 'koala',
            'priority' => 2,
            'urgency' => 1,
            'favourite' => 1,
            'pinned' => 1,
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
        $this->assertEquals(1, $content['pinned']);
        $this->assertEquals(5, $content['parent_id']);
        $this->assertEquals(2, $content['category_id']);

        //Should be HTTP_CREATED
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

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

        //Should be HTTP_CREATED
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

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
     *
     * @test
     * @return void
     */
    public function it_can_update_an_item()
    {
        DB::beginTransaction();
        $this->logInUser();

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
            'category_id' => 2
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

    /**
     * @test
     * @return void
     */
    public function it_can_delete_an_item()
    {
        DB::beginTransaction();
        $this->logInUser();

        $item = Item::first();

        $response = $this->call('DELETE', '/api/items/'.$item->id);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->call('DELETE', '/api/item/' . $item->id);
        $this->assertEquals(404, $response->getStatusCode());

        DB::rollBack();
    }
}