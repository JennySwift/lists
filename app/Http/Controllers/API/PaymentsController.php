<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Charge;
use Stripe\Stripe;

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

}
