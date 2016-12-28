<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

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
        $response = $this->call('GET', '/api/items');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_throws_an_exception_for_index_method_if_user_is_not_logged_in()
    {
        $response = $this->call('GET', '/api/items');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertArrayHasKey('error', $content);
        $this->assertContains('not logged in', $content['error']);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $content['status']);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_filter_the_items()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items?filter=au');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        foreach ($content as $item) {
            $this->assertContains('au', $item['title'], '', true);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     *
     */
    private function createAlarms()
    {
        $item = [
            'title' => 'numbat',
            'body' => 'koala',
            'priority' => 2,
            'favourite' => 1,
            'parent_id' => 5,
            'category_id' => 2,
            'alarm' => '2030-01-01 06:00:00',
        ];

        $response = $this->call('POST', '/api/items', $item);

        $item = [
            'title' => 'frog',
            'body' => 'body',
            'priority' => 2,
            'favourite' => 1,
            'parent_id' => 5,
            'category_id' => 2,
            'alarm' => '2030-01-01 10:00:00',
        ];

        $response = $this->call('POST', '/api/items', $item);
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
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content[0]);
        $this->assertCount(2, $content);

        foreach ($content as $item) {
            $this->assertNotNull($item['alarm']);
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
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        foreach ($content as $item) {
            $this->assertEquals(1, $item['favourite']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     *
     */
    private function createUrgentItems()
    {
        $item = [
            'title' => 'numbat',
            'body' => 'koala',
            'priority' => 2,
            'favourite' => 1,
            'parent_id' => 5,
            'category_id' => 2,
            'urgency' => 1,
        ];

        $response = $this->call('POST', '/api/items', $item);

        $item = [
            'title' => 'frog',
            'body' => 'body',
            'priority' => 2,
            'favourite' => 1,
            'parent_id' => 5,
            'category_id' => 2,
            'urgency' => 2,
        ];

        $response = $this->call('POST', '/api/items', $item);
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
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content[0]);
        //Only items with urgency 1 are retrieved
        $this->assertCount(1, $content);

        foreach ($content as $item) {
            $this->assertEquals(1, $item['urgency']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_deleted_items()
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