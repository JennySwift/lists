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

        $this->checkInvoiceKeysExist($content[0]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * This test assumes the user has no invoices yet
     * @test
     * @return void
     */
    public function it_does_not_error_if_there_are_no_invoices()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/invoices');
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_show_an_upcoming_invoice()
    {
        $this->logInUser();

        $response = $this->call('GET', '/api/invoices?upcoming=true');
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkInvoiceKeysExist($content);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     */
//    public function it_can_show_an_invoice()
//    {
//        $this->logInUser();
//
//        //Get an invoice id
//        $response = $this->call('GET', '/api/invoices');
//        $content = json_decode($response->getContent(), true);
//        $invoiceId = $content[0]['id'];
//
//        $response = $this->call('GET', '/api/invoices/' . $invoiceId);
//        dd($response);
//        $content = json_decode($response->getContent(), true);
//        //dd($content);
//
//        $this->checkInvoiceKeysExist($content);
//
//        $this->assertEquals(1, $content['id']);
//
//        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
//    }

}