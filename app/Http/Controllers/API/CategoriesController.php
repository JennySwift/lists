<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Transformers\CategoryTransformer;
use App\Models\Category;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JavaScript;

class CategoriesController extends Controller
{
    private $fields = ['name'];

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
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $categories = Category::forCurrentUser()->orderBy('name', 'asc')->get();

        return $this->respondIndex($categories, new CategoryTransformer);
    }

    public function show(Request $request, Category $category)
    {
        return $this->respondShow($category, new CategoryTransformer);
    }

    /**
     *
     * @param CategoryStoreRequest $request
     * @return mixed
     */
    public function store(CategoryStoreRequest $request)
    {
        $category = new Category($request->only($this->fields));
        $category->user()->associate(Auth::user());
        $category->save();

        return $this->respondStore($category, new CategoryTransformer);
    }

    /**
     *
     * @param Request $request
     * @param Category $category
     * @return mixed
     */
    public function update(Request $request, Category $category)
    {
        $data = $this->getData($category, $request->only($this->fields));

        $category->update($data);

        return $this->respondUpdate($category, new CategoryTransformer);
    }

    /**
     *
     * @param Request $request
     * @param Category $category
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \ReflectionException
     */
    public function destroy(Request $request, Category $category)
    {
        return $this->destroyModel($category);
    }

}
