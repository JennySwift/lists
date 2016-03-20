<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class PushNotificationsTest
 */
class PushNotificationsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Commenting this out so I don't get a push notification whenever I run my tests
     * @test
     * @return void
     */
    public function it_can_send_a_push_notification()
    {
//        DB::beginTransaction();
//        $this->logInUser();
//
//        $notification = [
//            'title' => 'koala',
//            'message' => 'kangaroo',
//        ];
//
//        $response = $this->call('POST', '/api/pushNotifications', $notification);

        /**
         * @VP:
         * How do I test the response here? $response =
         * Illuminate\Http\Response {...}
         * #content: ""
         * ...
         * #statusCode: 200
         * ...
         * {"status":1,"request":"somebigstring"}
         *
         * And I want to test that "status" is 1.
         */

//        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

//        DB::rollBack();
    }
}