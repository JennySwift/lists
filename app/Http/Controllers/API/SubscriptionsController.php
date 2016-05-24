<?php

namespace App\Http\Controllers\API;

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

        $user->subscription($request->get('plan'))->swap();

        return response($user, Response::HTTP_OK);
    }

}
