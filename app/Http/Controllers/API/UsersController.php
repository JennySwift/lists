<?php

namespace App\Http\Controllers\API;

use App\User;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    /**
     * GET /api/users/{users}
     * @return Response
     */
    public function show()
    {
//        $user = $this->transform($this->createItem($user, new UserTransformer))['data'];
        return response(Auth::user(), Response::HTTP_OK);
    }
}
