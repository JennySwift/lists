<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Error\ApiConnection;
use Stripe\Error\Authentication;
use Stripe\Error\Base;
use Stripe\Error\Card;
use Stripe\Error\InvalidRequest;
use Stripe\Error\RateLimit;
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

        $charge = Charge::create([
            "amount" => 1000,
            "currency" => "aud",
            "customer" => $user->stripe_id
        ]);

        return response($charge->__toArray(), Response::HTTP_OK);
    }

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function subscribe(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $user = Auth::user();
        $customer = Customer::retrieve($user->stripe_id);
        $token = $customer->__toArray()['default_source'];

        $user->subscription($request->get('plan'))->swap();

//        $user->subscription($request->get('plan'))->create($token, [
//            'email' => $user->email
//        ]);

        return response($user, Response::HTTP_OK);
    }


}
