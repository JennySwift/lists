<?php

use App\User;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
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
        $this->assertArrayHasKey('path_to_item', $item);
        $this->assertArrayHasKey('category', $item);
    }
}
