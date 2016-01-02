<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Models\Item;
use App\Repositories\CategoriesRepository;
use App\Repositories\ItemsRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JavaScript;
use Auth;
use Debugbar;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Class ItemsController
 * @package App\Http\Controllers\API
 */
class ItemsController extends Controller
{
    /**
     * @var ItemsRepository
     */
    protected $itemsRepository;
    /**
     * @var CategoriesRepository
     */
    protected $categoriesRepository;

    /**
     * Create a new controller instance.
     *
     * @param ItemsRepository $itemsRepository
     */
    public function __construct(ItemsRepository $itemsRepository, CategoriesRepository $categoriesRepository)
    {
        $this->middleware('auth');
        $this->itemsRepository = $itemsRepository;
        $this->categoriesRepository = $categoriesRepository;
    }

    /**
     *
     * @return Response
     */
    public function pageLoad()
    {
        JavaScript::put([
            'categories' => $this->categoriesRepository->getCategories(),
            'favourites' => $this->itemsRepository->getFavourites(),
            'base_path' => base_path()
        ]);

        return view('pages.items.items-page');
    }

    /**
     * Get home items
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($request->has('pinned')) {
            return $this->itemsRepository->transform(Item::forCurrentUser()->where('pinned', 1)->get());
        }
        return $this->itemsRepository->transform($this->itemsRepository->getHomeItems());
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function filter(Request $request)
    {
        $typing = '%' . $request->get('typing') . '%';

        $items = Item::where('user_id', Auth::user()->id)
            ->where('title', 'LIKE', $typing)
            ->get();

        return $this->itemsRepository->transform($items);
    }
    
    /**
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $parent = false;

        $item = new Item($request->only([
            'title',
            'body',
            'priority',
            'favourite',
            'pinned',
        ]));

        if ($request->get('parent_id')) {
            $parent = Item::find($request->get('parent_id'));
            $item->parent()->associate($parent);
        }

        $item->user()->associate(Auth::user());
        $item->category()->associate(Category::find($request->get('category_id')));
        $item->index = $item->calculateIndex($request->get('index'), $parent);

        $item->save();

        return $this->showSomething($parent);
    }

    /**
     * Get items of a chosen parent (or home items if no parent)
     * Todo: make RESTful
     * @param $parent
     * @return Response|mixed
     */
    public function showSomething($parent)
    {
        if ($parent) {
            return $this->show($parent);
        }
        else {
            return $this->itemsRepository->transform($this->itemsRepository->getHomeItems());
        }
    }

    /**
     *
     * @param Item $item
     * @return array
     */
    public function show(Item $item)
    {
        $breadcrumb = $item->breadcrumb();
        $array = [];
        foreach (collect($breadcrumb) as $item) {
            $array[] = $item->transform();
        }

        return [
            'children' => $this->itemsRepository->transform($item->children()->order('priority')->get()),
            'breadcrumb' => $array
        ];
    }

    /**
     *
     * @param Request $request
     * @param Item $item
     * @return Response
     */
    public function update(Request $request, Item $item)
    {
        // Create an array with the new fields merged
        $data = array_compare($item->toArray(), $request->only([
            'priority', 'title', 'body', 'favourite', 'pinned'
        ]));

        $item->update($data);

        if ($request->has('parent_id')) {
            $item->parent()->associate(Item::findOrFail($request->get('parent_id')));
            $item->save();
        }

        if ($request->has('category_id')) {
            $item->category()->associate(Category::findOrFail($request->get('category_id')));
            $item->save();
        }

        if ($request->has('moveItem')) {
            $this->itemsRepository->moveItem($request, $item);
        }

        return response($item->transform(), Response::HTTP_OK);
    }

    /**
     *
     * @param Item $item
     * @return Response
     */
    public function destroy(Item $item)
    {
        try {
            $item->delete();
            return response([], Response::HTTP_NO_CONTENT);
        }
        catch (\Exception $e) {
            //Integrity constraint violation
            if ($e->getCode() === '23000') {
                $message = 'Item could not be deleted. It is in use.';
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

    /**
     *
     * @return mixed
     */
    public function undoDeleteItem()
    {
        $item = Item::where('user_id', Auth::user()->id)
            ->onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->first();

        $item->restore();

        return $item->transform();
    }
}
