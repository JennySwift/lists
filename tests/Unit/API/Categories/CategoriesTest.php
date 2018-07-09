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
        $content = $this->getContent($response);
//      dd($content);

        $this->checkCategoryKeysExist($content[0]);

        $this->assertResponseOk($response);
    }

    /**
     * @test
     */
    public function it_can_show_a_category()
    {
        $this->logInUser();

        $category = Category::forCurrentUser()->first();

        $response = $this->call('GET', '/api/categories/' . $category->id);
        $content = $this->getContent($response);
        //dd($content);

        $this->checkCategoryKeysExist($content);

        $this->assertEquals(1, $content['id']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
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
//        dd($response);
        $content = $this->getContent($response);
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
        $this->logInUser();

        $category = [

        ];

        $response = $this->apiCall('POST', '/api/categories', $category);
        $content = $this->getContent($response);
//            dd($content);

        $this->checkValidationResponse($content, ['name']);
        $this->assertResponseInvalid($response);
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
        $content = $this->getContent($response);
        //dd($content);

        $this->checkCategoryKeysExist($content);

        $this->assertEquals('numbat', $content['name']);

        $this->assertResponseOk($response);

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