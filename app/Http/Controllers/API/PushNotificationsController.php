<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PushNotificationsController extends Controller
{

    /**
     * @param Request $request
     */
    public function sendPushNotification(Request $request)
    {
        curl_setopt_array($ch = curl_init(), array(
            CURLOPT_URL => "https://api.pushover.net/1/messages.json",
            CURLOPT_POSTFIELDS => array(
                "token" => env('PUSHOVER_APP_TOKEN'),
                "user" => env('PUSHOVER_USER_KEY'),
                "title" => $request->get('title'),
                "message" => $request->get('message'),
            ),
            CURLOPT_SAFE_UPLOAD => true,
        ));
        curl_exec($ch);
        curl_close($ch);
    }
}
