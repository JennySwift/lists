<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\StoreItemRequest;
use App\Http\Transformers\ItemTransformer;
use App\Models\Category;
use App\Models\Item;
use App\Repositories\CategoriesRepository;
use App\Repositories\ItemsRepository;
use Auth;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JavaScript;
use Pusher;

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
     * Create a new controller instance.
     *
     * @param ItemsRepository $itemsRepository
     */
    public function __construct(ItemsRepository $itemsRepository)
    {
        $this->itemsRepository = $itemsRepository;
    }

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->has('alarm')) {
            $items = Item::forCurrentUser()->whereNotNull('alarm')->get();
        }

        elseif ($request->has('favourites')) {
            $items = $this->itemsRepository->getFavourites();
        }

        elseif ($request->has('trashed')) {
            $items = $this->itemsRepository->getTrashed();
        }

        elseif ($request->has('urgent')) {
            $items = $this->itemsRepository->getUrgentItems();
        }

        elseif ($request->has('filter')) {
            $items = $this->itemsRepository->getFilteredItems($request);
        }

        else {
            $items = $this->itemsRepository->getHomeItems();
        }

        $items = $this->transform($this->createCollection($items, new ItemTransformer))['data'];
        return response($items, Response::HTTP_OK);
    }

    /**
     * POST /api/itemRequests
     * @param StoreItemRequest $request
     * @return Response
     */
    public function store(StoreItemRequest $request)
    {
        $currentUser = Auth::user();

        if ($currentUser && $this->itemsRepository->itemAlreadyExists($request)) {
            //Checking $currentUser is true because if it's feedback sent from one of my apps,
            //the itemAlreadyExists method will throw an exception because the user isn't logged in
            return response([
                'error' => "You already have this item here.",
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
        else {
            $item = new Item($request->only([
                'title',
                'body',
                'priority',
                'urgency',
                'favourite',
                'alarm',
                'not_before',
                'recurring_unit',
                'recurring_frequency'
            ]));

            if ($request->get('recurring_unit') === 'none') {
                $item->recurring_unit = null;
            }

            //This is because the alarm was getting set to 0000-00-00 00:00:00 when no alarm was specified
            if ($request->has('alarm') && !$request->get('alarm')) {
                $item->alarm = null;
            }

            $parent = false;
            if ($request->get('parent_id')) {
                $parent = Item::find($request->get('parent_id'));
                $item->parent()->associate($parent);
            }

            if ($currentUser) {
                $item->user()->associate(Auth::user());
            }

            else {
                //User is not logged in. It could be a feedback request from one of my apps. Add the item to my items (user_id 1).
                $item->user()->associate(1);
            }

            $item->category()->associate(Category::find($request->get('category_id')));
            $item->index = $item->calculateIndex($request->get('index'), $parent);

            $item->save();

            $item = $this->transform($this->createItem($item, new ItemTransformer))['data'];
            return response($item, Response::HTTP_CREATED);
        }
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
            $item = $this->itemsRepository->getHomeItems();
            $item = $this->transform($this->createItem($item, new ItemTransformer))['data'];
            return response($item, Response::HTTP_OK);
        }
    }

    /**
     * GET /api/items/{items}
     * @param Item $item
     * @return Response
     */
    public function show(Item $item)
    {
        $breadcrumb = $item->breadcrumb();
        $array = [];
        foreach (collect($breadcrumb) as $item) {
            $array[] = $item;
        }

        $children = $item->children()->order('priority')->get();
        $children = $this->transform($this->createCollection($children, new ItemTransformer))['data'];
        $array = $this->transform($this->createCollection($array, new ItemTransformer))['data'];

        $item = $this->transform($this->createItem($item, new ItemTransformer))['data'];
        $item['children'] = $children;
        $item['breadcrumb'] = $array;

        return response($item, Response::HTTP_OK);
    }

    /**
     *
     * @param Request $request
     * @param Item $item
     * @return Response
     */
    public function update(Request $request, Item $item)
    {
        if ($request->has('updatingNextTimeForRecurringItem')) {
            $item = $this->itemsRepository->updateNextTimeForRecurringItem($item);
        }

        else {
            $data = array_compare($item->toArray(), $request->only([
                'priority',
                'urgency',
                'title',
                'body',
                'favourite',
                'alarm',
                'not_before',
                'recurring_unit',
                'recurring_frequency'
            ]));

            //So the recurring unit can be removed
            if ($request->get('recurring_unit') === 'none') {
                $data['recurring_unit'] = null;
            }

            //So the not before time can be removed
            if ($request->exists('not_before') && !$request->get('not_before')) {
                $data['not_before'] = null;
            }

            //So the recurring frequency can be removed
            if ($request->exists('recurring_frequency') && !$request->get('recurring_frequency')) {
                $data['recurring_frequency'] = null;
            }

            //So the alarm of an item can be removed
            if ($request->has('alarm') && !$request->get('alarm')) {
                $data['alarm'] = null;
            }
            //So the urgency of an item can be removed
            if ($request->has('urgency') && !$request->get('urgency')) {
                $data['urgency'] = null;
            }

            //So the body of an item can be removed
            if ($request->exists('body') && !$request->get('body')) {
                $data['body'] = '';
            }

            $item->update($data);

            if ($request->has('parent_id')) {
                //So the parent_id can be removed (so the item moves to the top-most level, home)
                if ($request->get('parent_id') === 'none') {
                    $item->parent()->dissociate();
                }
                else {
                    $item->parent()->associate(Item::findOrFail($request->get('parent_id')));
                }
                $item->save();
            }

            if ($request->has('category_id')) {
                $item->category()->associate(Category::findOrFail($request->get('category_id')));
                $item->save();
            }

            if ($request->has('moveItem')) {
                $this->itemsRepository->moveItem($request, $item);
            }
        }

        $item = $this->transform($this->createItem($item, new ItemTransformer))['data'];
        return response($item, Response::HTTP_OK);
    }

    /**
     * Force delete all the user's trashed items
     */
    public function emptyTrash()
    {
        $items = Item::forCurrentUser()->onlyTrashed()->get();

        foreach ($items as $item) {
            $item->forceDelete();
        }

        return response([], Response::HTTP_NO_CONTENT);
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
        } catch (\Exception $e) {
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
        $item = Item::forCurrentUser()
            ->onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->first();

        $item->restore();

        $item = $this->transform($this->createItem($item, new ItemTransformer))['data'];
        return response($item, Response::HTTP_OK);
    }

    /**
     *
     * @param $id
     * @return Response
     */
    public function restore($id)
    {
        $item = Item::withTrashed()->where('id', $id)->first();
        $item->restore();

        $item = $this->transform($this->createItem($item, new ItemTransformer))['data'];
        return response($item, Response::HTTP_OK);
    }
}
