<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Token;

/**
 * Class CustomersTest
 */
class CustomersTest extends BillingTest
{
    use DatabaseTransactions;

    /**
     * @test
     * @group billing
     */
    public function it_can_create_a_customer()
    {
        $this->logInUser();

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

        $response = $this->apiCall('POST', '/api/customers', $billing);
//        dd($response);
        $content = json_decode($response->getContent(), true);
//         dd($content);

        $this->assertNotNull($content['stripe_id']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @group billing
     */
    public function it_throws_an_exception_creating_a_customer_with_invalid_details()
    {
        $this->logInUser();
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $data = Token::create([
            'card' => [
                "number" => "4000000000000069",
                "exp_month" => 11,
                "exp_year" => 2030,
                "cvc" => "123"
            ]
        ]);

        $billing = [
            'token' => $data['id']
        ];

        $response = $this->apiCall('POST', '/api/customers', $billing);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertEquals('Your card has expired.', $content['error']);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $content['status']);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }


    /**
     * @test
     * @group billing
     */
    public function it_can_update_a_customer()
    {
        DB::beginTransaction();
        $this->logInUser();
        $this->createCustomer();

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $data = Token::create([
            'card' => [
                "number" => '4012888888881881',
                "exp_month" => 1,
                "exp_year" => 2020,
                "cvc" => "456"
            ]
        ]);

        $billing = [
            'token' => $data['id']
        ];

        $response = $this->apiCall('PUT', '/api/customers/' . $this->user->stripe_id, $billing);
//        dd($response);
        $content = json_decode($response->getContent(), true);
//         dd($content);

        $this->assertNotNull($content['id']);
        $this->assertEquals($this->user->email, $content['email']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        
        DB::rollback();
    }
    
    /**
     * @test
     * @group billing
     */
    public function it_can_delete_a_customer()
    {
        DB::beginTransaction();
        $this->logInUser();
        $this->createCustomer();

        $stripeId = $this->user->stripe_id;
        $this->assertNotNull($this->user->stripe_id);

        $response = $this->apiCall('DELETE', '/api/customers/'. $this->user->stripe_id);
        $this->assertEquals(204, $response->getStatusCode());

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $customer = Customer::retrieve($stripeId)->__toArray();
        $this->assertTrue($customer['deleted']);
        $this->user = User::find($this->user->id);

        //Check stripe details are removed from database
        $this->assertEquals(0, $this->user->stripe_active);
        $this->assertNull($this->user->stripe_id);
        $this->assertNull($this->user->stripe_subscription);
        $this->assertNull($this->user->stripe_plan);
        $this->assertNull($this->user->last_four);
        $this->assertNull($this->user->trial_ends_at);
        $this->assertNull($this->user->subscription_ends_at);

        $response = $this->call('DELETE', '/api/customers/' . $this->user->stripe_id);
        $this->assertEquals(422, $response->getStatusCode());
    
        DB::rollBack();
    }
}