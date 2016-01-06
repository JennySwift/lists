<?php

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

}