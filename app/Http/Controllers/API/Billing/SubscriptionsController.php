<?php

namespace App\Http\Controllers\API\Billing;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        $user->subscription($newPlan)->swap();

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

}
