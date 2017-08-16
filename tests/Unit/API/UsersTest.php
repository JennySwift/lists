<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class UsersTestTest
 */
class UsersTestTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_show_the_logged_in_user()
    {
        $this->logInUser();

        $response = $this->call('GET', '/api/users/');
        $content = $this->getContent($response);
        //dd($content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('Jenny', $content['name']);
        $this->assertEquals('cheezyspaghetti@gmail.com', $content['email']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }
}