<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use JavaScript;
use Auth;

class CategoriesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *
     * @return Response|\Illuminate\View\View
     */
    public function pageLoad()
    {
        JavaScript::put([
            'categories' => $this->index()
        ]);

        return view('categories');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Category::where('user_id', Auth::user()->id)
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $category = new Category($request->only(['name']));
        $category->user()->associate(Auth::user());
        $category->save();

        return response($category->transform(), Response::HTTP_CREATED);
    }

}
