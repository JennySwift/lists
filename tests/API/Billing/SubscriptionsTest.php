<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Stripe\Stripe;
use Stripe\Token;

/**
 * Class SubscriptionsTest
 */
class SubscriptionsTest extends BillingTest
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_subscribe_an_existing_customer_to_the_monthly_plan()
    {
        $this->logInUser(1);
        $this->createCustomer();

        $billing = [
            'plan' => 'monthly'
        ];

        $response = $this->apiCall('PUT', '/api/subscriptions', $billing);
//        dd($response);
        $content = json_decode($response->getContent(), true);
//         dd($content);

        $this->checkStripeKeysExist($content);

        $this->assertTrue($content['stripe_active']);
        $this->assertEquals('monthly', $content['stripe_plan']);
        $this->assertNull($content['trial_ends_at']);
        $this->assertNull($content['subscription_ends_at']);


        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_errors_if_user_tries_to_change_plans_and_they_are_already_on_that_plan()
    {
        $this->logInUser(1);

        $this->createCustomer();
        $this->subscribeUserToPlan('monthly');
        
        $this->assertEquals('monthly', $this->user->stripe_plan);

        $billing = [
            'plan' => 'monthly'
        ];

        $response = $this->apiCall('PUT', '/api/subscriptions', $billing);
//        dd($response);
        $content = json_decode($response->getContent(), true);
//         dd($content);

        $this->assertEquals('You are already on the monthly plan.', $content['error']);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $content['status']);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_subscribe_an_existing_customer_to_the_yearly_plan()
    {
        $this->logInUser();
        $this->createCustomer();
        $billing = [
            'plan' => 'yearly'
        ];

        $response = $this->apiCall('PUT', '/api/subscriptions', $billing);
        $content = json_decode($response->getContent(), true);
//         dd($content);

        $this->checkStripeKeysExist($content);

        $this->assertTrue($content['stripe_active']);
        $this->assertEquals('yearly', $content['stripe_plan']);
        $this->assertNull($content['trial_ends_at']);
        $this->assertNull($content['subscription_ends_at']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_cancel_a_subscription()
    {
        DB::beginTransaction();
        $userId = 1;
        $this->logInUser($userId);

        if (!$this->user->stripe_id) {
            $this->createCustomer();
        }

        if ($this->user->stripe_plan !== 'monthly') {
            $this->subscribeUserToPlan('monthly');
        }

        $this->assertEquals(1, $this->user->stripe_active);
        $this->assertEquals('monthly', $this->user->stripe_plan);
        $this->assertNull($this->user->subscription_ends_at);

        $response = $this->apiCall('DELETE', '/api/subscriptions');
//        dd($response);
        $content = json_decode($response->getContent(), true);
//         dd($content);

        $this->assertFalse($content['stripe_active']);
        $this->assertEquals('monthly', $content['stripe_plan']);
        //Check the subscription ends one month from today (assuming the seeder was run today)
        $this->assertEquals(Carbon::today()->addMonth()->format('Y-m-d'), Carbon::createFromFormat('Y-m-d H:i:s', $content['subscription_ends_at'])->format('Y-m-d'));
        $this->assertNotNull($content['stripe_id']);
        $this->assertNotNull($content['stripe_subscription']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_resume_a_cancelled_subscription()
    {
        DB::beginTransaction();
        $userId = 1;
        $this->logInUser($userId);

        if (!$this->user->stripe_id) {
            $this->createCustomer();
        }

        if ($this->user->stripe_plan !== 'monthly') {
            $this->subscribeUserToPlan('monthly');
        }

        if ($this->user->stripe_active) {
            $this->cancelSubscription();
        }

        $this->assertEquals(0, $this->user->stripe_active);
        $this->assertEquals('monthly', $this->user->stripe_plan);
        //Check the subscription is cancelled
        $this->assertEquals(Carbon::today()->addMonth()->format('Y-m-d'), Carbon::createFromFormat('Y-m-d H:i:s', $this->user->subscription_ends_at)->format('Y-m-d'), $this->user->subscription_ends_at);

        $response = $this->apiCall('PUT', '/api/subscriptions/resume');
//        dd($response);
        $content = json_decode($response->getContent(), true);
//         dd($content);

        $this->assertTrue($content['stripe_active']);
        $this->assertEquals('monthly', $content['stripe_plan']);
        //Check the subscription ends one month from today (assuming the seeder was run today)
        $this->assertNull($content['subscription_ends_at']);
        $this->assertNotNull($content['stripe_id']);
        $this->assertNotNull($content['stripe_subscription']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        DB::rollBack();
    }
}