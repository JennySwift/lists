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
        //Todo:
//        $this->deleteItem(Item::find(607));
//        $this->deleteItem(Item::find(244));

        $response = $this->call('GET', '/api/items');
        $content = $this->getContent($response);
        $data = $content['data'];
//      dd($content);

        $this->checkItemKeysExist($data[0]);

        //Check the items include the deleted items
        Todo:
//        $count = 0;
//        foreach ($data as $item) {
//            if ($item['deletedAt']) {
//                $count++;
//            }
//        }
//        $this->assertEquals(2, $count);

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
//        Todo:
//        $this->deleteItem(Item::find(607));
//        $this->deleteItem(Item::find(244));

        $response = $this->call('GET', '/api/items?max=4');
        $content = $this->getContent($response);
        $data = $content['data'];
//      dd($content);

        $this->checkItemKeysExist($data[0]);

        //Check the items include the deleted items
//        $count = 0;
//        foreach ($content as $item) {
//            if ($item['deletedAt']) {
//                $count++;
//            }
//        }
//        $this->assertEquals(2, $count);

        $this->assertCount(4, $data);

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_does_not_get_more_items_than_it_should_when_no_max_is_specified()
    {
        $this->logInUser();


        $response = $this->call('GET', '/api/items');
        $content = $this->getContent($response);
        $data = $content['data'];
//      dd($content);

        $this->checkItemKeysExist($data[0]);

        $this->checkPaginationKeysExist($content['pagination']);

        $this->assertCount(5, $data);

        $this->assertResponseOk($response);



    }

    /**
     * @test
     */
    public function it_can_go_to_page_two()
    {
        $this->logInUser();

        $response = $this->call('GET', '/api/items?page=2');
        $content = $this->getContent($response);
        $data = $content['data'];
//      dd($content);

        $this->checkItemKeysExist($data[0]);

        $this->checkPaginationKeysExist($content['pagination']);

        $this->assertCount(5, $data);

        $this->assertEquals(2, $content['pagination']['current_page']);
        $this->assertEquals('http://localhost/api/items?page=3', $content['pagination']['next_page_url']);
        $this->assertEquals(6, $content['pagination']['from']);
        $this->assertEquals(10, $content['pagination']['to']);

        $this->assertResponseOk($response);
    }

    /**
     * Should be sorted first by priority, then by notBefore, then by category name, then by id
     * @test
     */
    public function it_gets_the_items_in_the_right_order()
    {
        $this->logInUser();

        $response = $this->call('GET', '/api/items?page=2');
        $content = $this->getContent($response);
        $data = $content['data'];
//      dd($content);

        $this->checkItemKeysExist($data[0]);
        $this->checkPaginationKeysExist($content['pagination']);

        $this->assertEquals(5, $data[3]['category_id']);
        $this->assertEquals(5, $data[4]['category_id']);

        $this->assertResponseOk($response);

        //Check the not before is ordered correctly
        $response = $this->call('GET', '/api/items?page=3');
        $content = $this->getContent($response);
        $data = $content['data'];
//      dd($content);

        $this->checkItemKeysExist($data[0]);
        $this->checkPaginationKeysExist($content['pagination']);

        $this->assertEquals('Do something yesterday', $data[1]['title']);
        $this->assertEquals('Do something today', $data[2]['title']);
        $this->assertEquals('Do something tomorrow', $data[3]['title']);
        $this->assertEquals('Do something whenever', $data[4]['title']);

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
    public function it_can_filter_the_top_level_by_priority()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items?priority=2');
        $content = $this->getContent($response);
        $data = $content['data'];
//      dd($content);

        $this->checkItemKeysExist($data[0]);

        foreach ($data as $item) {
            $this->assertEquals(2, $item['priority']);
        }

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_filter_the_top_level_by_min_priority()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items?min_priority=2&max=8');
        $content = $this->getContent($response);
        $data = $content['data'];
//      dd($content);

        $this->checkItemKeysExist($data[0]);

        foreach ($data as $item) {
            $this->assertLessThan(3, $item['priority']);
        }

        $this->assertCount(8, $data);

        $this->assertResponseOk($response);
    }

    /**
     * This is for the autocomplete, where no pagination is needed
     * @test
     * @return void
     */
    public function it_can_filter_all_items_by_title_with_no_pagination()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items?filter=push');
        $data = $this->getContent($response);
//        $data = $content['data'];
//      dd($content);

        $this->checkItemKeysExist($data[0]);

        foreach ($data as $item) {
            $this->assertContains('push', $item['title'], '', true);
        }

        $this->assertResponseOk($response);
    }

    /**
     * This is for the autocomplete, where no pagination is needed
     * @test
     * @return void
     */
    public function it_can_filter_all_items_by_note_with_no_pagination()
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
        $response = $this->call('GET', '/api/items?filter=good&field=body');
        $data = $this->getContent($response);
//        $data = $content['data'];
//      dd($content);

        $this->checkItemKeysExist($data[0]);

        foreach ($data as $item) {
            $this->assertContains('good', $item['body'], '', true);
        }
        
        $this->assertCount(1, $data);

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
//    public function it_gets_the_items_with_an_alarm()
//    {
//        $this->logInUser();
//        $this->createAlarms();
//        $response = $this->call('GET', '/api/items?alarm=true');
//        $content = $this->getContent($response);
////      dd($content);
//
//        $this->checkItemKeysExist($content[0]);
//        $this->assertCount(2, $content);
//
//        foreach ($content as $item) {
//            $this->assertNotNull($item['alarm']);
//        }
//
//        $this->assertResponseOk($response);
//    }

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
//    public function it_gets_the_urgent_items()
//    {
//        $this->logInUser();
//        $this->createUrgentItems();
//        $response = $this->call('GET', '/api/items?urgent=true');
//        $content = $this->getContent($response);
//        $data = $content['data'];
////      dd($content);
//
//        $this->checkItemKeysExist($data[0]);
//        //Only items with urgency 1 are retrieved
//        $this->assertCount(1, $data);
//
//        foreach ($content as $item) {
//            $this->assertEquals(1, $item['urgency']);
//        }
//
//        $this->assertResponseOk($response);
//    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_deleted_items()
    {
        $this->logInUser();

        //First delete some items. This should delete items many because the item has children.
//        $this->deleteItem(Item::find(1));


        $response = $this->call('GET', '/api/items?trashed=true');
        $content = $this->getContent($response);
        $data = $content['data'];
//      dd($content);

        $this->checkItemKeysExist($data[0]);
        $this->assertEquals(3, count($data));
        $this->assertEquals(3, $content['pagination']['total']);

        //This item has no parent, so it can be restored (todo: check item indeed has no parent)
        $this->assertNull($data[0]['parent_id']);
        $this->assertTrue($data[0]['canBeRestored']);

        //This item has a deleted parent, so it cannot be restored (todo: check parent is indeed deleted)
        $this->assertEquals(30, $data[1]['parent_id']);
        $this->assertFalse($data[1]['canBeRestored']);

        //Todo: test canBeRestored is true for an item that is deleted, but whose parent is not deleted

        foreach ($data as $item) {
            $this->assertArrayHasKey('deleted_at', $item);
        }

        $this->assertResponseOk($response);
    }

}