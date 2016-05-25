<?php

namespace App\Http\Controllers\API\Billing;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Stripe\Customer;
use Stripe\Stripe;
use Auth;

class CustomersController extends Controller
{
    /**
     * POST /api/customers
     * @param Request $request
     */
    public function store(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
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
     * PUT /api/customers/{customers}
     * @param Request $request
     * @param Customer $customer
     * @return Customer
     */
    public function update(Request $request, Customer $customer)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $user = Auth::user();

        $customer->email = $user->email;
        $customer->source = $request->get('token');
        $customer->save();

        return response($customer->__toArray(), Response::HTTP_OK);
    }

    /**
     * DELETE /api/customers/{customers}
     * @param Request $request
     * @param Customer $customer
     * @return Response
     */
    public function destroy(Request $request, Customer $customer)
    {
        $customer->delete();

//        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
//        dd(Customer::retrieve(Auth::user()->stripe_id)->__toArray());

        //Remove Stripe values from database
        $user = Auth::user();
        $user->stripe_active = 0;
        $user->stripe_id = null;
        $user->stripe_subscription = null;
        $user->stripe_plan = null;
        $user->last_four = null;
        $user->trial_ends_at = null;
        $user->subscription_ends_at = null;

        $user->save();

        return response([], Response::HTTP_NO_CONTENT);
    }
}
