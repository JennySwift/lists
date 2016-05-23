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

        try {
            $charge = Charge::create([
                "amount" => 1000,
                "currency" => "aud",
                "customer" => $user->stripe_id
            ]);

            return response($charge->__toArray(), Response::HTTP_OK);
        }
        catch(Card $e) {
            dd($e->getMessage());
            // The card has been declined
        }
        catch(RateLimit $e) {
            dd($e->getMessage());
        }
        catch(InvalidRequest $e) {
            dd($e->getMessage());
        }
        catch(Authentication $e) {
            dd($e->getMessage());
        }
        catch(ApiConnection $e) {
            dd($e->getMessage());
        }
        catch(Base $e) {
            dd($e->getMessage());
        }
        catch(Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     *
     * @param Request $request
     */
    private function createCustomer(Request $request)
    {
        $user = Auth::user();

        $customer = Customer::create([
            "source" => $request->get('token'),
            "email" => Auth::user()->email
        ]);

        //Save the user's customer id in the database
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
        $user->subscription($request->get('plan'))->create($request->get('token'), [
            'email' => $user->email
        ]);

        return response($user, Response::HTTP_OK);
    }


}
