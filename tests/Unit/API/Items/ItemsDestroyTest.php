<?php

use App\Models\Item;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
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
     *
     * @test
     * @return void
     */
    public function it_can_empty_the_trash()
    {
        $this->logInUser();
        $this->createAndDeleteItems();
        $this->checkTrashedItemsAreAsExpected($this->getTrashedItems());

        //Do the same for user 2, so we can test their trash won't empty
        $this->logInUser(2);
        $this->createAndDeleteItems();
        $this->checkTrashedItemsAreAsExpected($this->getTrashedItems());

        //Log in user 1 again
        $this->logInUser();

        //Check the number of item's the user has before emptying the trash
        $this->assertCount(858, Item::where('user_id', $this->user->id)->get());

        $response = $this->call('DELETE', '/api/items/emptyTrash');
//        dd($response);

        //Check the user still has the same number of items that are not in the trash
        $this->assertCount(858, Item::where('user_id', $this->user->id)->get());

        $trashedItemsAfterEmpty = $this->getTrashedItems();

        $this->assertCount(0, $trashedItemsAfterEmpty);

        //Check user 2's trash has not been emptied
        $this->logInUser(2);
        $this->assertCount(3, $this->getTrashedItems());
    }

    /**
     *
     */
    private function createAndDeleteItems()
    {
        $item1 = [
            'title' => 'one',
            'body' => 'a note',
            'priority' => 2,
            'favourite' => 0,
            'parent_id' => null,
            'category_id' => 2
        ];

        $content1 = $this->getContent($this->createItem($item1));

        $item2 = [
            'title' => 'two',
            'body' => '',
            'priority' => 2,
            'favourite' => 0,
            'parent_id' => $content1['id'],
            'category_id' => 2
        ];

        $content2 = $this->getContent($this->createItem($item2));
        $this->assertEquals($content1['id'], $content2['parent_id']);

        $item3 = [
            'title' => 'three',
            'body' => '',
            'priority' => 2,
            'favourite' => 0,
            'parent_id' => $content2['id'],
            'category_id' => 2
        ];

        $content3 = $this->getContent($this->createItem($item3));
        $this->assertEquals($content2['id'], $content3['parent_id']);

        //Now delete the top level item
        $this->assertEquals(Response::HTTP_NO_CONTENT,
            $this->deleteItemById($content1['id'])->getStatusCode()
        );
        //Check the item and its descendants are now deleted
        $this->assertEquals(Response::HTTP_NOT_FOUND,
            $this->deleteItemById($content1['id'])->getStatusCode()
        );
        $this->assertEquals(Response::HTTP_NOT_FOUND,
            $this->deleteItemById($content2['id'])->getStatusCode()
        );
        $this->assertEquals(Response::HTTP_NOT_FOUND,
            $this->deleteItemById($content3['id'])->getStatusCode()
        );

    }

    /**
     *
     * @return mixed
     */
    private function getTrashedItems()
    {
        $response = $this->call('GET', '/api/items?trashed=true');
        $content = $this->getContent($response);
//      dd($content);

        $this->assertResponseOk($response);

        return $content;
    }

    /**
     *
     * @param $trashedItems
     */
    private function checkTrashedItemsAreAsExpected($trashedItems)
    {
        $this->checkItemKeysExist($trashedItems[0]);
        $this->assertCount(3, $trashedItems);
        $this->assertEquals('one', $trashedItems[0]['title']);
        $this->assertEquals('two', $trashedItems[1]['title']);
        $this->assertEquals('three', $trashedItems[2]['title']);

        foreach ($trashedItems as $item) {
            $this->assertArrayHasKey('deleted_at', $item);
            $this->assertNotNull($item['deleted_at']);
        }
    }


}