<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Stripe\Stripe;
use Stripe\Token;

/**
 * Class BillingTest
 */
class BillingTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @group billing
     * @return void
     */
    public function it_can_bill_a_user()
    {
        $this->logInUser(2);
        $this->createCustomer();

        $response = $this->apiCall('POST', '/api/payments/bill');
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
     *
     */
    protected function createCustomer()
    {
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
    }

    /**
     *
     * @param $plan
     * @return Response
     */
    protected function subscribeUserToPlan($plan)
    {
        $billing = [
            'plan' => $plan
        ];

        $response = $this->apiCall('PUT', '/api/subscriptions', $billing);
        //Update $this->user because their subscription has changed
        $this->user = User::find($this->user->id);

        return $response;
    }

    /**
     *
     */
    protected function cancelSubscription()
    {
        $response = $this->apiCall('DELETE', '/api/subscriptions');
        //Update $this->user because their subscription has been cancelled
        $this->user = User::find($this->user->id);
    }
}