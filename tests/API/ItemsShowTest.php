<?php

use App\Models\Item;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

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

}