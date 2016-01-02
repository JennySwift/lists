<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ItemsTest
 */
class ItemsTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * @test
     * @return void
     */
    public function it_gets_the_items()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items');
        $content = json_decode($response->getContent(), true);
//      dd($content);
    
        $this->checkItemKeysExist($content[0]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_gets_the_pinned_items()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/items?pinned=true');
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkItemKeysExist($content[0]);

        foreach ($content as $item) {
            $this->assertEquals(1, $item['pinned']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

}