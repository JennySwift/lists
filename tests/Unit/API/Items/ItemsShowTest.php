<?php

use App\Models\Item;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class ItemsShowTest
 */
class ItemsShowTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_show_an_item()
    {
        $this->logInUser();
        $this->restoreTrashedItems();

        $item = Item::forCurrentUser()->first();

        $response = $this->call('GET', '/api/items/' . $item->id);
        $content = $this->getContent($response);
        $item = $content['data'];
        $data = $content['data']['children']['data'];
//        dd($content);

        $this->checkItemKeysExist($item);
        $this->checkItemKeysExist($data[0]);
        $this->checkItemKeysExist($content['data']['breadcrumb'][0]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->checkPaginationKeysExist($content['data']['children']['pagination']);
    }

    /**
     * @test
     */
    public function it_can_show_an_item_without_any_trashed_items()
    {
        $this->logInUser();

        $item = Item::find(28);

        //Check it has a deleted child
        $child = $item->children()->withTrashed()->first();
        $this->assertNotNull( $child->deleted_at);


        $response = $this->call('GET', '/api/items/' . $item->id);
        $content = $this->getContent($response);
        $item = $content['data'];
        $data = $content['data']['children']['data'];

        $this->assertCount(0, $data);


//        $this->checkItemKeysExist($item);
//        $this->checkItemKeysExist($data[0]);
//        $this->checkItemKeysExist($content['data']['breadcrumb'][0]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->checkPaginationKeysExist($content['data']['children']['pagination']);
    }

    /**
     * @test
     */
    public function it_can_show_an_item_including_trashed_items()
    {
        $this->logInUser();

        $item = Item::find(28);

        //Check it has a deleted child
        $child = $item->children()->withTrashed()->first();
        $this->assertNotNull( $child->deleted_at);


        $response = $this->call('GET', '/api/items/' . $item->id . '?with_trashed=true');
        $content = $this->getContent($response);
        $item = $content['data'];
        $data = $content['data']['children']['data'];

        $this->assertCount(1, $data);
        $this->assertNotNull($data[0]['deletedAt']);

//        $this->checkItemKeysExist($item);
//        $this->checkItemKeysExist($data[0]);
//        $this->checkItemKeysExist($content['data']['breadcrumb'][0]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->checkPaginationKeysExist($content['data']['children']['pagination']);
    }


    /**
     * @test
     */
    public function it_does_not_show_more_than_the_max_when_showing_the_children_of_an_item()
    {
        $this->logInUser();
        $this->restoreTrashedItems();

        $item = Item::forCurrentUser()->first();

        $response = $this->call('GET', '/api/items/' . $item->id . '?max=1');
        $content = $this->getContent($response);
        $item = $content['data'];
        $data = $content['data']['children']['data'];
//        dd($content);

        $this->checkItemKeysExist($item);
        $this->checkItemKeysExist($data[0]);
        $this->checkItemKeysExist($content['data']['breadcrumb'][0]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->checkPaginationKeysExist($content['data']['children']['pagination']);
        $this->checkPaginationKeysExist($content['pagination']);

        $this->assertCount(1, $data);
    }

    /**
     * @test
     */
    public function it_can_get_page_two_for_the_children_of_an_item()
    {
        $this->logInUser();
        $this->restoreTrashedItems();

//        $item = Item::forCurrentUser()->first();
        $item = Item::find(1);

        $response = $this->call('GET', '/api/items/' . $item->id . '?max=3&page=2');
        $content = $this->getContent($response);
        $item = $content['data'];
        $data = $content['data']['children']['data'];
//        dd($content);
        $this->checkItemKeysExist($item);
        $this->checkItemKeysExist($data[0]);
        $this->checkItemKeysExist($content['data']['breadcrumb'][0]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->checkPaginationKeysExist($content['data']['children']['pagination']);

        $this->assertCount(3, $data);
        $this->assertEquals(2, $content['data']['children']['pagination']['current_page']);


        $this->assertEquals('1.4', $data[0]['title']);
    }


    /**
     * @test
     */
    public function it_throws_an_exception_if_item_does_not_exist()
    {
        $this->logInUser();

        $response = $this->call('GET', '/api/items/5000');
        $content = $this->getContent($response);
//        dd($content);

        $this->assertEquals('Item not found.', $content['error']);
        $this->assertEquals(404, $content['status']);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

}