<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

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
    public function it_gets_the_categories()
    {
        $this->logInUser();
        $response = $this->call('GET', '/api/categories');
        $content = json_decode($response->getContent(), true);
    //  dd($content);

        $this->checkCategoryKeysExist($content[0]);

        $this->assertEquals(200, $response->getStatusCode());
    }

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

    /**
     * @test
     * @return void
     */
    public function it_throws_an_exception_for_category_store_method_without_required_fields()
    {
        DB::beginTransaction();
        $this->logInUser();

        $category = [

        ];

        $response = $this->apiCall('POST', '/api/categories', $category);
        $content = json_decode($response->getContent(), true);
//            dd($content);

        $this->assertArrayHasKey('name', $content);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

        DB::rollBack();
    }


    /**
     * @test
     * @return void
     */
    public function it_can_update_a_category()
    {
        DB::beginTransaction();
        $this->logInUser();

        $category = Category::forCurrentUser()->first();

        $response = $this->call('PUT', '/api/categories/'.$category->id, [
            'name' => 'numbat'
        ]);
        $content = json_decode($response->getContent(), true);
        //dd($content);

        $this->checkCategoryKeysExist($content);

        $this->assertEquals('numbat', $content['name']);

        $this->assertEquals(200, $response->getStatusCode());

        DB::rollBack();
    }

    /**
     * @test
     * @return void
     */
    public function it_can_delete_a_category()
    {
        DB::beginTransaction();
        $this->logInUser();

        $category = Category::first();

        $response = $this->call('DELETE', '/api/categories/'.$category->id);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->call('DELETE', '/api/categories/' . $category->id);
        $this->assertEquals(404, $response->getStatusCode());

        DB::rollBack();
    }

}