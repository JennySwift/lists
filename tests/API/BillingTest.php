<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class BillingTest
 */
class BillingTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_bill_a_user()
    {
        DB::beginTransaction();
        $this->logInUser();

        $billing = [
            'name' => 'koala'
        ];

        $response = $this->call('POST', '/api/payments', $billing);
        dd($response);
        $content = json_decode($response->getContent(), true);
        // dd($content);

        $this->checkBillingKeysExist($content);

        $this->assertEquals('koala', $content['name']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        DB::rollBack();
    }
}