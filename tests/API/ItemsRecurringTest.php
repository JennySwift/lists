<?php

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
    public function it_can_calculate_the_next_time_for_a_recurring_item()
    {
        DB::beginTransaction();
        $this->logInUser();
    
        $item = Item::forCurrentUser()->whereNotNull('recurring_unit')->first();

        $this->assertEquals('minute', $item->recurring_unit);
        $this->assertEquals(1, $item->recurring_frequency);
        $this->assertEquals(Carbon::tomorrow()->format('Y-m-d H:i:s'), $item->not_before);

        $response = $this->call('PUT', '/api/items/'.$item->id, [
            'updatingNextTimeForRecurringItem' => true
        ]);
        $content = json_decode($response->getContent(), true);
        //dd($content);

        $this->checkitemKeysExist($content);

        $expectedNextTime = Carbon::tomorrow()->addMinutes(1)->format('Y-m-d H:i:s');

        $this->assertEquals($expectedNextTime, $content['notBefore']);

        $this->assertEquals(200, $response->getStatusCode());
        
        DB::rollBack();
    }
}