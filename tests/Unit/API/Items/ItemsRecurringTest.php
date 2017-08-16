<?php

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class ItemsRecurringTest
 */
class ItemsRecurringTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * @test
     * @return void
     */
    public function it_can_calculate_the_next_time_for_a_recurring_item_that_has_a_not_before_time_in_the_future()
    {
        $this->logInUser();
        $item = $this->getFirstRecurringItem();
        $this->checkItemIsAsExpected($item);

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'updatingNextTimeForRecurringItem' => true
        ]);
        $content = $this->getContent($response);

        $this->checkItemKeysExist($content);

        $expectedNextTime = Carbon::tomorrow()->addMinutes(1)->format('Y-m-d H:i:s');

        $this->assertEquals($expectedNextTime, $content['notBefore']);

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_calculate_the_next_time_for_a_recurring_item_that_has_a_not_before_time_in_the_future_and_a_recurring_frequency_of_5()
    {
        $this->logInUser();
        $item = $this->getFirstRecurringItem();
        $this->checkItemIsAsExpected($item);

        //Make the recurring frequency 5
        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'recurring_frequency' => 5
        ]);
        $content = $this->getContent($response);
        //dd($content);
        $this->assertEquals(5, $content['recurringFrequency']);

        //Check it calculates the next time correctly, for when the instance of the recurring item in the future is completed
        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'updatingNextTimeForRecurringItem' => true
        ]);
        $content = $this->getContent($response);
        //dd($content);

        $this->checkItemKeysExist($content);

        $expectedNextTime = Carbon::tomorrow()->addMinutes(5)->format('Y-m-d H:i:s');

        $this->assertEquals($expectedNextTime, $content['notBefore']);

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_calculate_the_next_time_for_a_recurring_item_that_has_a_not_before_time_in_the_future_and_a_recurring_frequency_of_5_and_a_recurring_unit_of_months()
    {
        $this->logInUser();
        $item = $this->getFirstRecurringItem();
        $this->checkItemIsAsExpected($item);

        //Make the recurring frequency 5 and the recurring frequency 'month' and the not before date way in the future (so it's fixed and testable rather than dynamic)
        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'not_before' => '2050-01-10 15:30:00',
            'recurring_unit' => 'month',
            'recurring_frequency' => 5
        ]);
        $content = $this->getContent($response);
        //dd($content);
        $this->assertEquals('2050-01-10 15:30:00', $content['notBefore']);
        $this->assertEquals('month', $content['recurringUnit']);
        $this->assertEquals(5, $content['recurringFrequency']);

        //Check it calculates the next time correctly, for when the instance of the recurring item in the future is completed
        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'updatingNextTimeForRecurringItem' => true
        ]);
        $content = $this->getContent($response);
        //dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals('2050-06-10 15:30:00', $content['notBefore']);

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_calculate_the_next_time_for_a_recurring_item_that_has_a_not_before_time_in_the_past_and_a_recurring_frequency_of_2_and_a_recurring_unit_of_years()
    {
        $this->logInUser();
        $item = $this->getFirstRecurringItem();
        $this->checkItemIsAsExpected($item);

        //Make the recurring frequency 2 and the recurring frequency 'year' and the not before date in the past
        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'not_before' => '2016-01-10 15:30:00',
            'recurring_unit' => 'year',
            'recurring_frequency' => 2
        ]);
        $content = $this->getContent($response);
        //dd($content);
        $this->assertEquals('2016-01-10 15:30:00', $content['notBefore']);
        $this->assertEquals('year', $content['recurringUnit']);
        $this->assertEquals(2, $content['recurringFrequency']);

        //Check it calculates the next time correctly, for when the instance of the recurring item in the future is completed
        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'updatingNextTimeForRecurringItem' => true
        ]);
        $content = $this->getContent($response);
        //dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals('2018-01-10 15:30:00', $content['notBefore']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_calculate_the_next_time_for_a_recurring_item_that_has_a_not_before_time_three_years_ago_and_a_recurring_frequency_of_2_and_a_recurring_unit_of_years()
    {
        $this->logInUser();
        $item = $this->getFirstRecurringItem();
        $this->checkItemIsAsExpected($item);

        //Make the recurring frequency 2 and the recurring frequency 'year' and the not before date in the past
        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'not_before' => '2013-01-10 15:30:00',
            'recurring_unit' => 'year',
            'recurring_frequency' => 2
        ]);
        $content = $this->getContent($response);
        //dd($content);
        $this->assertEquals('2013-01-10 15:30:00', $content['notBefore']);
        $this->assertEquals('year', $content['recurringUnit']);
        $this->assertEquals(2, $content['recurringFrequency']);

        //Check it calculates the next time correctly, for when the instance of the recurring item in the future is completed
        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'updatingNextTimeForRecurringItem' => true
        ]);
        $content = $this->getContent($response);
        //dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals('2019-01-10 15:30:00', $content['notBefore']);

        $this->assertResponseOk($response);
    }


    /**
     * Todo This test is very slow to run!
     * @test
     * @return void
     */
    public function it_can_calculate_the_next_time_for_a_recurring_item_that_has_a_not_before_time_in_the_past()
    {
        //Skipping because slow to run!
//        $this->markTestSkipped();
        $this->logInUser();
        $item = $this->getFirstRecurringItem();
        $this->checkItemIsAsExpected($item);

        //Make the not before time in the past
        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'not_before' => '2016-03-01 13:30:05'
        ]);
        $content = $this->getContent($response);
        //dd($content);
        $this->assertEquals('2016-03-01 13:30:05', $content['notBefore']);

        //Todo: it's this line that's making it really slow to run
        //Check it calculates the next time correctly, for when the instance of the recurring item in the past is completed
        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'updatingNextTimeForRecurringItem' => true
        ]);
        $content = $this->getContent($response);
        //dd($content);

        $this->checkItemKeysExist($content);


        $expectedNextTime = Carbon::now();

        //Make the expected seconds right for the test
        if ($expectedNextTime->second < 5) {
            $expectedNextTime->second = 5;
        }
        else {
            $expectedNextTime->minute++;
            $expectedNextTime->second = 5;
        }

        $this->assertEquals($expectedNextTime, $content['notBefore']);

        $this->assertResponseOk($response);
    }

    /**
     *
     * @return mixed
     */
    private function getFirstRecurringItem()
    {
        $item = Item::forCurrentUser()->whereNotNull('recurring_unit')->first();

        return $item;
    }

    /**
     *
     * @param $item
     */
    private function checkItemIsAsExpected($item)
    {
        $this->assertEquals('minute', $item->recurring_unit);
        $this->assertEquals(1, $item->recurring_frequency);
        $this->assertEquals(Carbon::tomorrow()->format('Y-m-d H:i:s'), $item->not_before);
    }


}