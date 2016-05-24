<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Stripe\Stripe;
use Stripe\Token;

/**
 * Class BillingTest
 */
class BillingTest extends TestCase
{
//    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_bill_a_user()
    {
        $this->logInUser(2);

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $data = Token::create([
            'card' => [
                "number" => "4242424242424242",
                "exp_month" => 11,
                "exp_year" => 2030,
                "cvc" => "123"
            ]
        ]);

        $billing = [
            'token' => $data['id']
        ];

        $response = $this->apiCall('POST', '/api/payments/bill', $billing);
//        dd($response);
        $content = json_decode($response->getContent(), true);
//         dd($content);

        $this->assertEquals('1000', $content['amount']);
        $this->assertEquals('aud', $content['currency']);
        $this->assertTrue($content['paid']);
        $this->assertEquals('succeeded', $content['status']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_subscribe_an_existing_customer_to_the_monthly_plan()
    {
//        DB::beginTransaction();
        $this->logInUser(1);

//        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
//        $data = Token::create([
//            'card' => [
//                "number" => "4242424242424242",
//                "exp_month" => 11,
//                "exp_year" => 2030,
//                "cvc" => "123"
//            ]
//        ]);

        $billing = [
//            'token' => $data['id'],
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

//        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_subscribe_an_existing_customer_to_the_yearly_plan()
    {
//        DB::beginTransaction();
        $this->logInUser();

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
//        $data = Token::create([
//            'card' => [
//                "number" => "4242424242424242",
//                "exp_month" => 11,
//                "exp_year" => 2030,
//                "cvc" => "123"
//            ]
//        ]);

        $billing = [
//            'token' => $data['id'],
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

//        DB::rollBack();
    }
}