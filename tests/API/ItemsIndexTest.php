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
     * @test
     * @return void
     */
    public function it_gets_the_pinned_items()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items?pinned=true');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        foreach ($content as $item) {
            $this->assertEquals(1, $item['pinned']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_items_with_an_alarm()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items?alarm=true');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

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
     * @test
     * @return void
     */
    public function it_gets_the_urgent_items()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items?urgent=true');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

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