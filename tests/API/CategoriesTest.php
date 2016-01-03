<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class CategoriesTest
 */
class CategoriesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @return void
     */
    public function it_can_create_a_category()
    {
        DB::beginTransaction();
        $this->logInUser();

        $category = [
            'name' => 'koala'
        ];

        $response = $this->call('POST', '/api/categories', $category);
        $content = json_decode($response->getContent(), true);
//      dd($content);

        $this->checkCategoryKeysExist($content);

        $this->assertEquals('koala', $content['name']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        DB::rollBack();
    }

}