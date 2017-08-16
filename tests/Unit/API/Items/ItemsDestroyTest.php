<?php

use App\Models\Item;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

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
        $this->logInUser();

        $item = Item::first();

        $this->assertEquals(204, $this->deleteItem($item)->getStatusCode());

        $this->assertEquals(404, $this->deleteItem($item)->getStatusCode());
    }

    /**
     * todo
     * @test
     * @return void
     */
    public function it_can_empty_the_trash()
    {
        $this->markTestIncomplete();
        $this->logInUser();
        $response = $this->call('GET', '/api/items?trashed=true');
        $content = $this->getContent($response);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        foreach ($content as $item) {
            $this->assertArrayHasKey('deleted_at', $item);
        }

        $this->assertResponseOk($response);
    }


}