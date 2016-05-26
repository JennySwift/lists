<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Stripe\Plan;
use Stripe\Stripe;

/**
 * Class SubscriptionPlansTestTest
 */
class SubscriptionPlansTestTest extends TestCase
{
    use DatabaseTransactions;


    /**
     * @test
     * This is just so I can create plans quickly after I reset the test data in the Stripe user interface
     */
    public function it_can_create_a_subscription_plan()
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        Plan::create([
            "amount" => 500,
            "interval" => "month",
            "name" => "monthly",
            "currency" => "aud",
            "id" => "monthly"
        ]);

        Plan::create([
            "amount" => 6000,
            "interval" => "year",
            "name" => "yearly",
            "currency" => "aud",
            "id" => "yearly"
        ]);
    }

    /**
     * @test
     * @group billing
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