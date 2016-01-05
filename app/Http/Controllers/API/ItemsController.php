<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreItemRequest;
use App\Models\Category;
use App\Models\Item;
use App\Repositories\CategoriesRepository;
use App\Repositories\ItemsRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JavaScript;
use Auth;
use Debugbar;
use DB;

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
        else if ($request->has('favourites')) {
            return $this->itemsRepository->transform($this->itemsRepository->getFavourites());
        }
        else if ($request->has('trashed')) {
            return $this->itemsRepository->transform($this->itemsRepository->getTrashed());
        }
        else if ($request->has('filter')) {
            $items = Item::forCurrentUser()
                ->where('title', 'LIKE', '%' . $request->get('filter') . '%')
                ->get();

            return $this->itemsRepository->transform($items);
        }

        return $this->itemsRepository->transform($this->itemsRepository->getHomeItems());
    }
    
    /**
     *
     * @param Request $request
     * @return Response
     */
    public function store(StoreItemRequest $request)
    {
        $parent = false;

        $item = new Item($request->only([
            'title',
            'body',
            'priority',
            'urgency',
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

        return $item->transform();
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

        $children = $this->itemsRepository->transform($item->children()->order('priority')->get());
        $item = $item->transform();
        $item['children'] = $children;
        $item['breadcrumb'] = $array;

        return $item;
    }

    /**
     *
     * @param Request $request
     * @param Item $item
     * @return Response
     */
    public function update(Request $request, Item $item)
    {
//         Create an array with the new fields merged
        $data = array_compare($item->toArray(), $request->only([
            'priority',
            'urgency',
            'title',
            'body',
            'favourite',
            'pinned'
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
     * Force delete all the user's trashed items
     */
    public function emptyTrash()
    {
        $items = Item::forCurrentUser()->onlyTrashed()->get();

        var_dump(count($items));

        foreach ($items as $item) {
            $item->forceDelete();
        }
        var_dump(Item::withTrashed()->count());
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
