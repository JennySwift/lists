<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Class PagesTest
 */
class PagesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_redirects_the_user_if_not_authenticated()
    {
        $response = $this->call('GET', '/');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertTrue($response->isRedirection());
        $this->assertEquals($this->baseUrl . '/login', $response->headers->get('Location'));
    }

    /**
     * @test
     * @return void
     */
    public function it_redirects_to_the_home_page_when_a_user_logs_in()
    {
        $response = $this->call('POST', '/login');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertTrue($response->isRedirection());
        $this->assertEquals($this->baseUrl, $response->headers->get('Location'));
    }

    /**
     * @VP:
     * How do I test what I'm trying to test here and in the above method?
     * And before I defined $redirectTo in AuthController.php, the user was redirect to '/' after login,
     * and '/home' after registering. Why? I have trouble trying to follow Laravel's code.
     * @test
     * @return void
     */
    public function it_redirects_to_the_home_page_when_a_user_registers()
    {
//        $response = $this->call('POST', '/auth/register');
//
//        $this->assertEquals(302, $response->getStatusCode());
//        $this->assertTrue($response->isRedirection());
//        $this->assertRedirectedTo($this->baseUrl);
//        $this->seePageIs('/');
//        $this->assertRedirectedToRoute('/');
    }

}