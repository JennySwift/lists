<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Error\Card;
use Stripe\Stripe;
use Auth;

class PaymentsController extends Controller
{

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function bill(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $user = Auth::user();

        if (!$user->stripe_id) {
            $user = $this->createCustomer($request);
        }

        try {
            $charge = Charge::create([
                "amount" => 1000,
                "currency" => "aud",
                "customer" => $user->stripe_id
            ]);

            return response($charge->__toArray(), Response::HTTP_OK);
        }
        catch(Card $e) {
            dd($e);
            // The card has been declined
        }
    }

    /**
     *
     * @param Request $request
     */
    private function createCustomer(Request $request)
    {
        $customer = Customer::create([
            "source" => $request->get('token'),
            "description" => "Example customer"
        ]);

        //Save the user's customer id in the database
        $user = Auth::user();
        $user->stripe_id = $customer->id;
        $user->save();

        return $user;
    }

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function subscribe(Request $request)
    {
        $user = Auth::user();
        $user->subscription($request->get('plan'))->create($request->get('token'));

        return response($user, Response::HTTP_OK);
    }


}
