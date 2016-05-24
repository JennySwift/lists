<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class SubscriptionPlansTestTest
 */
class SubscriptionPlansTestTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_subscription_plans()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/subscriptionPlans');
//        dd($response);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertEquals('yearly', $content['data'][0]['id']);
        $this->assertEquals('6000', $content['data'][0]['amount']);
        $this->assertEquals('aud', $content['data'][0]['currency']);
        $this->assertEquals('year', $content['data'][0]['interval']);

        $this->assertEquals('monthly', $content['data'][1]['id']);
        $this->assertEquals('500', $content['data'][1]['amount']);
        $this->assertEquals('aud', $content['data'][1]['currency']);
        $this->assertEquals('month', $content['data'][1]['interval']);

        $this->assertEquals(200, $response->getStatusCode());
    }
}