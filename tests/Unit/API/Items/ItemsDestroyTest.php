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

    private $expectedTrashSizeBeforeEmptying = 4;
    private $expectedUndeletedItemCount = 877;

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
//        $this->createAndDeleteItems();
        $this->checkTrashedItemsAreAsExpected($this->getTrashedItems());

        //Do the same for user 2, so we can test their trash won't empty
        $this->logInUser(2);
//        $this->createAndDeleteItems();
        $this->checkTrashedItemsAreAsExpected($this->getTrashedItems());

        //Log in user 1 again
        $this->logInUser();

        //Check the number of item's the user has before emptying the trash
        $this->assertCount($this->expectedUndeletedItemCount, Item::where('user_id', $this->user->id)->get());

        $response = $this->call('DELETE', '/api/items/emptyTrash');
//        dd($response);

        //Check the user still has the same number of items that are not in the trash
        $this->assertCount($this->expectedUndeletedItemCount, Item::where('user_id', $this->user->id)->get());

        $trashedItemsAfterEmpty = $this->getTrashedItems();

        $this->assertCount(0, $trashedItemsAfterEmpty);

        //Check user 2's trash has not been emptied
        $this->logInUser(2);
        $this->assertCount($this->expectedTrashSizeBeforeEmptying, $this->getTrashedItems());
    }

    /**
     * old test
     * @test
     * @return void
     */
//    public function it_can_restore_an_item_from_the_trash()
//    {
//        DB::beginTransaction();
//        $this->logInUser();
//
//        //Delete an item
//        Item::where('user_id', $this->user->id)->first()->delete();
//
//        $item = Item::forCurrentUser()
////            ->whereNotNull('deleted_at')
////            ->withTrashed()
//            ->first();
//
//        $response = $this->call('PUT', '/api/items/restore/'. $item->id, [
//            'deleted_at' => null
//        ]);
//
////        dd($response);
//        $content = $this->getContent($response);
////        dd($content);
//
//        $this->checkItemKeysExist($content);
//
//        $this->assertNull($content['deletedAt']);
//
//        //Check the children are restored, too
//        $this->assertCount(3, $item->children);
//
//        $this->assertResponseOk($response);
//
//        DB::rollBack();
//    }

    /**
     *
     * @test
     * @return void
     */
    public function it_can_restore_an_item_from_the_trash_if_it_does_not_have_deleted_parent()
    {
        $this->logInUser();
//        $this->createAndDeleteItems();
        $trashedItems = $this->getTrashedItems();
        $this->checkTrashedItemsAreAsExpected($trashedItems);

        //Check the number of item's the user has before restoring the item
        $this->assertCount($this->expectedUndeletedItemCount, Item::where('user_id', $this->user->id)->get());

        $id = $trashedItems[0]['id'];
        //Check the item cannot be found outside the trash
        $this->assertNull(Item::find($id));

        //Check it does not have a deleted parent
        $trashedParent = Item::onlyTrashed()->find($id)->parent()->onlyTrashed()->first();
        $this->assertNull($trashedParent);

        $response = $this->call('PUT', '/api/items/restore/' . $id);
//        dd($response);
        $this->assertResponseOk($response);

        //Check the user has one more item now that isn't in the trash
        $this->assertCount($this->expectedUndeletedItemCount + 1, Item::where('user_id', $this->user->id)->get());

        //Check the user has one less item in the trash
        $this->assertCount($this->expectedTrashSizeBeforeEmptying -1, $this->getTrashedItems());
        $restoredItem = Item::find($id);
        $this->assertNotNull($restoredItem);
    }

    /**
     *
     * @test
     * @return void
     */
    public function it_cannot_restore_an_item_from_the_trash_if_its_parent_is_deleted()
    {
        $this->logInUser();
//        $this->createAndDeleteItems();
        $trashedItems = $this->getTrashedItems();
        $this->checkTrashedItemsAreAsExpected($trashedItems);

        //Check the number of item's the user has before restoring the item
        $this->assertCount($this->expectedUndeletedItemCount, Item::where('user_id', $this->user->id)->get());

        $id = $trashedItems[2]['id'];
        //Check the item cannot be found outside the trash
        $this->assertNull(Item::find($id));

        //Check the item has a parent that is deleted
        $trashedItem = Item::onlyTrashed()->find($id);
        $trashedParent = $trashedItem->parent()->onlyTrashed()->first();
        $this->assertNotNull($trashedItem->deleted_at);
        $this->assertNotNull($trashedParent);
        $this->assertNotNull($trashedParent->deleted_at);

        $response = $this->call('PUT', '/api/items/restore/' . $id);
        $content = $this->getContent($response);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('This item cannot be restored, because its parent has been deleted. Restore the parent first.', $content['error']);

        //Check the user has the same number of items as before that are not in the trash
        $this->assertCount($this->expectedUndeletedItemCount, Item::where('user_id', $this->user->id)->get());

        //Check the user has the same number of items as before that are in the trash
        $this->assertCount($this->expectedTrashSizeBeforeEmptying, $this->getTrashedItems());

        $this->assertNull(Item::find($id));

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

        return $content['data'];
    }

    /**
     *
     * @param $trashedItems
     */
    private function checkTrashedItemsAreAsExpected($trashedItems)
    {
        $this->checkItemKeysExist($trashedItems[0]);
        $this->assertCount($this->expectedTrashSizeBeforeEmptying, $trashedItems);
        $this->assertEquals('A completed child', $trashedItems[0]['title']);
        $this->assertEquals('A completed item', $trashedItems[1]['title']);
        $this->assertEquals('A child of a completed item', $trashedItems[2]['title']);
        $this->assertEquals('Another completed item', $trashedItems[3]['title']);

        foreach ($trashedItems as $item) {
            $this->assertArrayHasKey('deleted_at', $item);
            $this->assertNotNull($item['deleted_at']);
        }
    }


}