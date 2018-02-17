<?php

use App\Models\Item;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class ItemsIndexTest
 */
class ItemsIndexTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_items()
    {
        $this->logInUser();

        //Delete some items to check they are still retrieved
        $this->deleteItem(Item::find(607));
        $this->deleteItem(Item::find(244));

        $response = $this->call('GET', '/api/items');
        $content = $this->getContent($response);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        //Check the items include the deleted items
        $count = 0;
        foreach ($content as $item) {
            if ($item['deletedAt']) {
                $count++;
            }
        }
        $this->assertEquals(2, $count);

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_does_not_get_more_items_than_it_should()
    {
        $this->logInUser();

        //Delete some items to check they are still retrieved
        $this->deleteItem(Item::find(607));
        $this->deleteItem(Item::find(244));

        $response = $this->call('GET', '/api/items?max=4');
        $content = $this->getContent($response);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        //Check the items include the deleted items
        $count = 0;
        foreach ($content as $item) {
            if ($item['deletedAt']) {
                $count++;
            }
        }
        $this->assertEquals(2, $count);

        $this->assertCount(4, $content);

        $this->assertResponseOk($response);
    }


    /**
     * @test
     * @return void
     */
    public function it_throws_an_exception_for_index_method_if_user_is_not_logged_in()
    {
        $response = $this->call('GET', '/api/items');
//        dd($response->getStatusCode());
        $content = $this->getContent($response);
//      dd($content);

        //Check no data is shown
        $this->assertNull($content);
        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertTrue($response->isRedirection());
        $this->assertEquals("http://localhost/login", $response->headers->get('Location'));

    }

    /**
     * @test
     * @return void
     */
    public function it_can_filter_the_items_by_title()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items?filter=au');
        $content = $this->getContent($response);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        foreach ($content as $item) {
            $this->assertContains('au', $item['title'], '', true);
        }

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_filter_the_items_by_note()
    {
        $this->logInUser();

        //First create item with a note
        $item = [
            'title' => 'Title',
            'body' => 'A really cool note',
            'priority' => 2,
            'favourite' => 0,
            'category_id' => 2
        ];

        $response = $this->createItem($item);
        $this->checkItemKeysExist($this->getContent($response));

        //Then filter items by note
        $response = $this->call('GET', '/api/items?filter=cool&field=body');
        $content = $this->getContent($response);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        foreach ($content as $item) {
            $this->assertContains('cool', $item['body'], '', true);
        }
        
        $this->assertCount(1, $content);

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_items_with_an_alarm()
    {
        $this->logInUser();
        $this->createAlarms();
        $response = $this->call('GET', '/api/items?alarm=true');
        $content = $this->getContent($response);
//      dd($content);

        $this->checkItemKeysExist($content[0]);
        $this->assertCount(2, $content);

        foreach ($content as $item) {
            $this->assertNotNull($item['alarm']);
        }

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_favourite_items()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items?favourites=true');
        $content = $this->getContent($response);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        foreach ($content as $item) {
            $this->assertEquals(1, $item['favourite']);
        }

        $this->assertResponseOk($response);
    }


    /**
     * @test
     * @return void
     */
    public function it_gets_the_urgent_items()
    {
        $this->logInUser();
        $this->createUrgentItems();
        $response = $this->call('GET', '/api/items?urgent=true');
        $content = $this->getContent($response);
//      dd($content);

        $this->checkItemKeysExist($content[0]);
        //Only items with urgency 1 are retrieved
        $this->assertCount(1, $content);

        foreach ($content as $item) {
            $this->assertEquals(1, $item['urgency']);
        }

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_deleted_items()
    {
        $this->logInUser();

        //First delete some items. This should delete items many because the item has children.
        $this->deleteItem(Item::find(1));


        $response = $this->call('GET', '/api/items?trashed=true');
        $content = $this->getContent($response);
//      dd($content);

        $this->checkItemKeysExist($content[0]);
        $this->assertEquals(121, count($content));

        //This item has no parent, so it can be restored
        $this->assertNull($content[0]['parent_id']);
        $this->assertTrue($content[0]['canBeRestored']);

        //This item has a deleted parent, so it cannot be restored
        $this->assertEquals(1, $content[1]['parent_id']);
        $this->assertFalse($content[1]['canBeRestored']);

        //Todo: test canBeRestored is true for an item that is deleted, but whose parent is not deleted

        foreach ($content as $item) {
            $this->assertArrayHasKey('deleted_at', $item);
        }

        $this->assertResponseOk($response);
    }

}