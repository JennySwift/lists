<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class InvoicesTest
 */
class InvoicesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_gets_the_invoices()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/invoices');
//        dd($response);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->assertArrayHasKey('amount_due', $content[0]);
        $this->assertArrayHasKey('paid', $content[0]);
        $this->assertArrayHasKey('total', $content[0]);

        $this->assertEquals(200, $response->getStatusCode());
    }

}