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
      dd($content);

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
            'favourite' => 1,
            'pinned' => 1,
            'parent_id' => 5,
            'category_id' => 2
        ];

        $response = $this->call('POST', '/api/items', $item);
        $content = json_decode($response->getContent(), true);
      dd($content);

        $this->checkItemKeysExist($content['children'][0]);

        $this->assertEquals('numbat', $content['children'][0]['title']);
        $this->assertEquals('koala', $content['children'][0]['body']);
        $this->assertEquals(2, $content['children'][0]['priority']);
        $this->assertEquals(1, $content['children'][0]['favourite']);
        $this->assertEquals(1, $content['children'][0]['pinned']);
        $this->assertEquals(5, $content['children'][0]['parent_id']);
        $this->assertEquals(2, $content['children'][0]['category_id']);

        //Should be HTTP_CREATED
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_throws_an_exception_for_store_method_without_required_fields()
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
            ->first();

//        $priority = $item->priority + 1;

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'title' => 'numbat',
            'body' => 'koala',
            'priority' => 2,
            'favourite' => 1,
            'pinned' => 1,
            'parent_id' => 5,
            'category_id' => 2
        ]);

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