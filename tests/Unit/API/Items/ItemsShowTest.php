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