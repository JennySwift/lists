<?php

namespace App\Http\Controllers\API\Billing;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Stripe\Plan;
use Stripe\Stripe;

class SubscriptionPlansController extends Controller
{
    /**
     * GET /api/subscriptionPlans
     * @return Response
     */
    public function index()
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $subscriptionPlans = Plan::all();

        return response($subscriptionPlans->__toArray(), Response::HTTP_OK);
    }
}
