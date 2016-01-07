<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Repositories\CategoriesRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use JavaScript;
use Auth;

class CategoriesController extends Controller
{
    /**
     * @var CategoriesRepository
     */
    private $categoriesRepository;

    /**
     * Create a new controller instance.
     * @param CategoriesRepository $categoriesRepository
     */
    public function __construct(CategoriesRepository $categoriesRepository)
    {
        $this->middleware('auth');
        $this->categoriesRepository = $categoriesRepository;
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
     *
     * @return Response
     */
    public function index()
    {
        $categories = Category::forCurrentUser()->orderBy('name', 'asc')->get();
        $categories = $this->categoriesRepository->transform($categories);
        return response($categories, Response::HTTP_OK);
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

    /**
     *
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function update(Request $request, Category $category)
    {
        // Create an array with the new fields merged
        $data = array_compare($category->toArray(), $request->only([
            'name'
        ]));
    
        $category->update($data);

        return response($category->transform(), Response::HTTP_OK);
    }

    /**
     *
     * @param Category $category
     * @return Response
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return response([], Response::HTTP_NO_CONTENT);
        }
        catch (\Exception $e) {
            //Integrity constraint violation
            if ($e->getCode() === '23000') {
                $message = 'Category could not be deleted. It is in use.';
            }
            else {
                $message = 'There was an error';
            }
            return response([
                'error' => $message,
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }

}
