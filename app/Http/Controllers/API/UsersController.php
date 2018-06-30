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
     * @var array
     */
    private $fields = ['last_route'];

    /**
     * GET /api/users/{users}
     * @return Response
     */
    public function show()
    {
//        $user = $this->transform($this->createItem($user, new UserTransformer))['data'];
        return response(Auth::user(), Response::HTTP_OK);
    }


    /**
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request)
    {
//        if ($user->id !== Auth::user()->id) {
//            return false;
//        }
        $user = Auth::user();

        $data = $this->getData($user, $request->only($this->fields));

        $user->update($data);

        return response($user, Response::HTTP_OK);
    }
}
