<?php

namespace App\Http\Controllers\API\Billing;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Stripe;
use Stripe\Subscription;

class SubscriptionsController extends Controller
{
    /**
     * PUT /api/subscriptions/{customers}
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $newPlan = $request->get('plan');

        if ($newPlan === $user->stripe_plan) {
            return response([
                'error' => 'You are already on the ' . $newPlan . ' plan.',
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        if ($user->stripe_plan === 'yearly' && $newPlan === 'monthly') {
            //Downgrading
            $subscriptionEnd = $user->subscription()->getSubscriptionEndDate();

//            $subscription = Subscription::retrieve($user->stripe_subscription);
//            $subscription->cancel();

            $user->subscription($user->stripe_plan)->cancelNow();

            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $subscription = Subscription::create([
                "customer" => $user->stripe_id,
                "plan" => $newPlan,
                'trial_end' => $subscriptionEnd->copy()->getTimestamp()
            ]);

            $user->trial_ends_at = $subscriptionEnd->copy()->format('Y-m-d H:i:s');
            $user->stripe_plan = $newPlan;
            $user->stripe_active = 1;
            $user->subscription_ends_at = null;
            $user->stripe_subscription = $subscription->id;
            $user->save();
        }

        else {
            $user->subscription($newPlan)->swap();
        }


        return response($user, Response::HTTP_OK);
    }

    /**
     * DELETE /api/subscriptions/{subscriptions}
     * @return Response
     */
    public function destroy()
    {
        $user = Auth::user();
        $user->subscription($user->getStripePlan())->cancel();

        return response($user, Response::HTTP_OK);
    }

    /**
     * PUT /api/subscriptions/resume
     * @return Response
     */
    public function resume()
    {
        $user = Auth::user();
        $user->subscription($user->getStripePlan())->resume();

        return response($user, Response::HTTP_OK);
    }

}
