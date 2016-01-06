<?php

use App\Models\Item;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ItemsDestroyTest
 */
class ItemsDestroyTest extends TestCase
{
    use DatabaseTransactions;

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


}