<?php

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class ItemsRecurringTest
 * Don't write tests with a not before date a long time ago,
 * and a short recurring unit such as minutes, or the test will be slow to run
 */
class ItemsRecurringTest extends TestCase
{
    use DatabaseTransactions;
    private $recurringItem;
    private $content;


    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $this->logInUser();
        $this->recurringItem = $this->getFirstRecurringItem();
        $this->checkItemIsAsExpected();
    }


    /**
     * @test
     * @return void
     */
    public function it_can_calculate_the_next_time_for_a_recurring_item_that_has_a_not_before_time_in_the_future()
    {
        $response = $this->rescheduleItem();
        $this->getItemContent($response);

        $this->assertEquals(Carbon::tomorrow()->addMinutes(1)->format('Y-m-d H:i:s'), $this->content['notBefore']);

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_calculate_the_next_time_for_a_recurring_item_that_has_a_not_before_time_in_the_future_and_a_recurring_frequency_of_5()
    {
        $response = $this->updateItem(['recurring_frequency' => 5]);
        $this->getItemContent($response);

        $this->assertEquals(5, $this->content['recurringFrequency']);

        $response = $this->rescheduleItem();

        $this->getItemContent($response);

        $this->assertEquals(Carbon::tomorrow()->addMinutes(5)->format('Y-m-d H:i:s'), $this->content['notBefore']);

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_calculate_the_next_time_for_a_recurring_item_that_has_a_not_before_time_in_the_future_and_a_recurring_frequency_of_5_and_a_recurring_unit_of_months()
    {
        $response = $this->updateItem([
            //Make this way in the future (so it's fixed and testable rather than dynamic)
            'not_before' => '2050-01-10 15:30:00',
            'recurring_unit' => 'month',
            'recurring_frequency' => 5
        ]);

        $this->getItemContent($response);
        $this->assertEquals('2050-01-10 15:30:00', $this->content['notBefore']);
        $this->assertEquals('month', $this->content['recurringUnit']);
        $this->assertEquals(5, $this->content['recurringFrequency']);

        $response = $this->rescheduleItem();
        $this->getItemContent($response);

        $this->assertEquals('2050-06-10 15:30:00', $this->content['notBefore']);

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_calculate_the_next_time_for_a_recurring_item_that_has_a_not_before_time_in_the_past_and_a_recurring_frequency_of_2_and_a_recurring_unit_of_years()
    {
        $response = $this->updateItem([
            //Make the not-before date in the past
            'not_before' => '2016-01-10 15:30:00',
            'recurring_unit' => 'year',
            'recurring_frequency' => 2
        ]);

        $this->getItemContent($response);

        $this->assertEquals('2016-01-10 15:30:00', $this->content['notBefore']);
        $this->assertEquals('year', $this->content['recurringUnit']);
        $this->assertEquals(2, $this->content['recurringFrequency']);

        $response = $this->rescheduleItem();
        $this->getItemContent($response);

        $this->assertEquals('2018-01-10 15:30:00', $this->content['notBefore']);

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_calculate_the_next_time_for_a_recurring_item_that_has_a_not_before_time_years_ago_and_a_recurring_frequency_of_2_and_a_recurring_unit_of_years()
    {
        $response = $this->updateItem([
            'not_before' => '2013-01-10 15:30:00',
            'recurring_unit' => 'year',
            'recurring_frequency' => 2
        ]);

        $this->getItemContent($response);

        $this->assertEquals('2013-01-10 15:30:00', $this->content['notBefore']);
        $this->assertEquals('year', $this->content['recurringUnit']);
        $this->assertEquals(2, $this->content['recurringFrequency']);

        $response = $this->rescheduleItem();
        $this->getItemContent($response);

        $this->assertEquals('2019-01-10 15:30:00', $this->content['notBefore']);

        $this->assertResponseOk($response);
    }


    /**
     * @test
     * @return void
     */
    public function it_can_calculate_the_next_time_for_a_recurring_item_that_has_a_not_before_time_in_the_past_and_a_recurring_unit_of_days()
    {
        $notBefore = Carbon::today()->subDays(5)->addHours(13)->addMinutes(5)->format('Y-m-d H:i:s');
        //Skipping because slow to run!
//        $this->markTestSkipped();
        $response = $this->updateItem([
            'not_before' => $notBefore,
            'recurring_unit' => 'day',
            'recurring_frequency' => 1
        ]);
        $this->getItemContent($response);

        $this->assertEquals($notBefore, $this->content['notBefore']);
        $this->assertEquals('day', $this->content['recurringUnit']);
        $this->assertEquals(1, $this->content['recurringFrequency']);

        $response = $this->rescheduleItem();
        $this->getItemContent($response);

        //This might fail if the test is run before 1:30pm?
        $expectedNextTime = Carbon::tomorrow()->addHours(13)->addMinutes(5)->format('Y-m-d H:i:s');

//        $expectedNextTime = Carbon::now();

        //Make the expected seconds right for the test
//        if ($expectedNextTime->second < 5) {
//            $expectedNextTime->second = 5;
//        }
//        else {
//            $expectedNextTime->minute++;
//            $expectedNextTime->second = 5;
//        }

        $this->assertEquals($expectedNextTime, $this->content['notBefore']);

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
     *
     */
    private function checkItemIsAsExpected()
    {
        $this->assertEquals('minute', $this->recurringItem->recurring_unit);
        $this->assertEquals(1, $this->recurringItem->recurring_frequency);
        $this->assertEquals(Carbon::tomorrow()->format('Y-m-d H:i:s'), $this->recurringItem->not_before);
    }

    /**
     *
     * @param $newData
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function updateItem($newData)
    {
        $response = $this->call('PUT', '/api/items/' . $this->recurringItem->id, $newData);

        return $response;
    }

    /**
     * For when the instance of the recurring item in the past or the future is completed
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function rescheduleItem()
    {
        $response = $this->updateItem(['updatingNextTimeForRecurringItem' => true]);

        return $response;
    }

    /**
     *
     * @param $response
     * @return mixed
     */
    private function getItemContent($response)
    {
        $this->content = $this->getContent($response);
        $this->checkItemKeysExist($this->content);

        return $this->content;
    }


}