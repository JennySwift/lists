<?php

namespace Tests;

use App\Models\Item;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Response;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';
    protected $user;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
//    public function createApplication()
//    {
//        $app = require __DIR__.'/../bootstrap/app.php';
//
//        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
//
//        return $app;
//    }

    /**
     * Make an API call
     * @param $method
     * @param $uri
     * @param array $parameters
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null $content
     * @return \Illuminate\Http\Response
     */
    public function apiCall($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $headers = $this->transformHeadersToServerVars([
            'Accept' => 'application/json'
        ]);
        $server = array_merge($server, $headers);

        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    /**
     *
     * @return mixed
     */
    public function logInUser($id = 1)
    {
        $user = User::find($id);
        $this->be($user);
        $this->user = $user;
        $this->actingAs($user, 'api');
    }

    /**
     *
     * @param $item
     */
    public function checkItemKeysExist($item)
    {
        $this->assertArrayHasKey('id', $item);
        $this->assertArrayHasKey('parent_id', $item);
        $this->assertArrayHasKey('title', $item);
        $this->assertArrayHasKey('body', $item);
        $this->assertArrayHasKey('index', $item);
        $this->assertArrayHasKey('category_id', $item);
        $this->assertArrayHasKey('priority', $item);
        $this->assertArrayHasKey('favourite', $item);
        $this->assertArrayHasKey('pinned', $item);
        $this->assertArrayHasKey('path', $item);
        $this->assertArrayHasKey('has_children', $item);
        $this->assertArrayHasKey('category', $item);
        $this->assertArrayHasKey('urgency', $item);
        $this->assertArrayHasKey('alarm', $item);
        $this->assertArrayHasKey('timeLeft', $item);
        $this->assertArrayHasKey('notBefore', $item);
        $this->assertArrayHasKey('recurringUnit', $item);
        $this->assertArrayHasKey('recurringFrequency', $item);
        $this->assertArrayHasKey('deletedAt', $item);
        $this->assertArrayHasKey('deleting', $item);
        $this->assertArrayHasKey('canBeRestored', $item);
    }

    public function checkPaginationKeysExist($items)
    {
        $this->assertArrayHasKey('total', $items);
        $this->assertArrayHasKey('per_page', $items);
        $this->assertArrayHasKey('current_page', $items);
        $this->assertArrayHasKey('last_page', $items);
        $this->assertArrayHasKey('next_page_url', $items);
        $this->assertArrayHasKey('prev_page_url', $items);
        $this->assertArrayHasKey('from', $items);
        $this->assertArrayHasKey('to', $items);
    }

    /**
     *
     * @param $category
     */
    public function checkCategoryKeysExist($category)
    {
        $this->assertArrayHasKey('id', $category);
        $this->assertArrayHasKey('name', $category);
        $this->assertArrayHasKey('path', $category);
    }

    /**
     *
     */
    protected function createUrgentItems()
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
     *
     */
    protected function createAlarms()
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
     * So my tests aren't wrecked up by the trashed items
     */
    protected function restoreTrashedItems()
    {
        $trashedItems = Item::onlyTrashed()->get();
        foreach ($trashedItems as $item) {
            $item->restore();
        }

    }

    /**
     *
     * @param $item
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function deleteItem($item)
    {
        $response = $this->call('DELETE', '/api/items/' . $item->id);

        return $response;
    }

    /**
     *
     * @param $id
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function deleteItemById($id)
    {
        $response = $this->call('DELETE', '/api/items/' . $id);

        return $response;
    }

    protected function assertResponseOk($response)
    {
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     *
     * @param $response
     * @return mixed
     */
    protected function getContent($response)
    {
        return json_decode($response->getContent(), true);
    }

    /**
     *
     * @param $item
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function createItem($item)
    {
        return $this->call('POST', '/api/items', $item);
    }
}



