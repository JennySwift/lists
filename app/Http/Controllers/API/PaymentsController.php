<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Stripe\Charge;
use Stripe\Error\Card;
use Stripe\Stripe;

class PaymentsController extends Controller
{

    public function bill(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        try {
            $charge = Charge::create([
                "amount" => 1000,
                "currency" => "aud",
                "source" => $request->get('token'),
                "description" => "Example charge"
            ]);

            return response($charge->__toArray(), Response::HTTP_OK);
        } catch(Card $e) {
            dd($e);
            // The card has been declined
        }
    }


}
