<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\StoreItemRequest;
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
     * Get home items
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($request->has('pinned')) {
            return response($this->itemsRepository->transform(Item::forCurrentUser()->where('pinned', 1)->get()),
                Response::HTTP_OK);
        }
        else {
            if ($request->has('alarm')) {
                return response($this->itemsRepository->transform(Item::forCurrentUser()->whereNotNull('alarm')->get()),
                    Response::HTTP_OK);
            }
            else {
                if ($request->has('favourites')) {
                    return response($this->itemsRepository->transform($this->itemsRepository->getFavourites()),
                        Response::HTTP_OK);
                }
                else {
                    if ($request->has('trashed')) {
                        return response($this->itemsRepository->transform($this->itemsRepository->getTrashed()),
                            Response::HTTP_OK);
                    }
                    else {
                        if ($request->has('urgent')) {
                            return response($this->itemsRepository->transform($this->itemsRepository->getUrgentItems()),
                                Response::HTTP_OK);
                        }
                        else {
                            if ($request->has('filter')) {
                                return response($this->itemsRepository->transform($this->itemsRepository->getFilteredItems($request)),
                                    Response::HTTP_OK);
                            }
                        }
                    }
                }
            }
        }

        return response($this->itemsRepository->transform($this->itemsRepository->getHomeItems()), RESPONSE::HTTP_OK);
    }

    /**
     *
     * @param StoreItemRequest $request
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
            'alarm'
        ]));

        //This is because the alarm was getting set to 0000-00-00 00:00:00 when no alarm was specified
        if ($request->has('alarm') && !$request->get('alarm')) {
            $item->alarm = null;
        }

        if ($request->get('parent_id')) {
            $parent = Item::find($request->get('parent_id'));
            $item->parent()->associate($parent);
        }

        $item->user()->associate(Auth::user());
        $item->category()->associate(Category::find($request->get('category_id')));
        $item->index = $item->calculateIndex($request->get('index'), $parent);

        $item->save();

        $pusher = new Pusher(env('PUSHER_PUBLIC_KEY'), env('PUSHER_SECRET_KEY'), env('PUSHER_APP_ID'));

        $data = 'weeeee';

        $pusher->trigger('myChannel', 'itemCreated', $data);

        return response($item->transform(), Response::HTTP_CREATED);
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
            return response($this->itemsRepository->transform($this->itemsRepository->getHomeItems()), Response::HTTP_OK);
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
        $data = array_compare($item->toArray(), $request->only([
            'priority',
            'urgency',
            'title',
            'body',
            'favourite',
            'pinned',
            'alarm'
        ]));

        //So the alarm of an item can be removed
        if ($request->has('alarm') && !$request->get('alarm')) {
            $data['alarm'] = null;
        }
        //So the urgency of an item can be removed
        if ($request->has('urgency') && !$request->get('urgency')) {
            $data['urgency'] = null;
        }

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

        return response($item->transform(), Response::HTTP_OK);
    }
}
