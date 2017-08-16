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
    protected $recurringItem;


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
        $content = $this->getContent($response);

        $this->checkItemKeysExist($content);

        $this->assertEquals(Carbon::tomorrow()->addMinutes(1)->format('Y-m-d H:i:s'), $content['notBefore']);

        $this->assertResponseOk($response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_calculate_the_next_time_for_a_recurring_item_that_has_a_not_before_time_in_the_future_and_a_recurring_frequency_of_5()
    {
        $response = $this->updateItem(['recurring_frequency' => 5]);
        $content = $this->getContent($response);

        $this->assertEquals(5, $content['recurringFrequency']);

        $response = $this->rescheduleItem();

        $content = $this->getContent($response);
        //dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals(Carbon::tomorrow()->addMinutes(5)->format('Y-m-d H:i:s'), $content['notBefore']);

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

        $content = $this->getContent($response);
        //dd($content);
        $this->assertEquals('2050-01-10 15:30:00', $content['notBefore']);
        $this->assertEquals('month', $content['recurringUnit']);
        $this->assertEquals(5, $content['recurringFrequency']);

        $response = $this->rescheduleItem();
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
        $response = $this->updateItem([
            //Make the not-before date in the past
            'not_before' => '2016-01-10 15:30:00',
            'recurring_unit' => 'year',
            'recurring_frequency' => 2
        ]);

        $content = $this->getContent($response);

        $this->assertEquals('2016-01-10 15:30:00', $content['notBefore']);
        $this->assertEquals('year', $content['recurringUnit']);
        $this->assertEquals(2, $content['recurringFrequency']);

        $response = $this->rescheduleItem();
        $content = $this->getContent($response);
        //dd($content);

        $this->checkItemKeysExist($content);

        $this->assertEquals('2018-01-10 15:30:00', $content['notBefore']);

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

        $content = $this->getContent($response);

        $this->assertEquals('2013-01-10 15:30:00', $content['notBefore']);
        $this->assertEquals('year', $content['recurringUnit']);
        $this->assertEquals(2, $content['recurringFrequency']);

        $response = $this->rescheduleItem();
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
        $response = $this->updateItem(['not_before' => '2016-03-01 13:30:05']);
        $content = $this->getContent($response);

        $this->assertEquals('2016-03-01 13:30:05', $content['notBefore']);

        //Todo: it's this line that's making it really slow to run
        $response = $this->rescheduleItem();
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


}