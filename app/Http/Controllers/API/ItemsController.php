<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Models\Item;
use App\Repositories\CategoriesRepository;
use App\Repositories\ItemsRepository;
use Illuminate\Http\Request;
use JavaScript;
use Auth;
use Debugbar;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ItemsController extends Controller
{
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
            'items' => $this->itemsRepository->getHomeItems(),
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
            return Item::forCurrentUser()->where('pinned', 1)->get();
        }
        return $this->itemsRepository->getHomeItems();
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

        return $items;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $parent = Item::find($request->get('parent_id'));
        $new_item = $request->get('new_item');
        $index = $request->get('index');

        $item = new Item([
            'title' => $new_item['title'],
            'body' => $new_item['body'],
            'category_id' => $new_item['category_id'],
            'priority' => $new_item['priority']
        ]);

        if ($parent) {
            $item->parent_id = $parent->id;
        }

        $item->index = $item->calculateIndex($index, $parent);

        $item->user()->associate(Auth::user());
        $item->save();

        return $this->showSomething($parent);
    }

    /**
     * Get items of a chosen parent (or home items if no parent)
     * @param $parent
     * @return Response|mixed
     */
    public function showSomething($parent)
    {
        if ($parent) {
            return $this->show($parent->id);
        }
        else {
            return $this->index();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $item = Item::find($id);

        return [
            'children' => $item->children()->order('priority')->get(),
            'breadcrumb' => $item->breadcrumb()
        ];
    }

    public function updateItem(Request $request)
    {
        $data = $request->get('item');
        $item = Item::find($data['id']);
        $category = Category::find($data['category_id']);
        $item->category()->associate($category);
        $item->priority = $data['priority'];
        $item->title = $data['title'];
        $item->body = $data['body'];
        $item->favourite = $data['favourite'];
        $item->save();
        return $item;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $item = Item::find($id);
        $parent = Item::find($request->get('parent_id'));
        $old_index = $request->get('old_index');
        $new_index = $request->get('new_index');

        if ($request->get('new_parent')) {
            $new_parent = Item::find($request->get('new_parent_id'));

            $this->itemsRepository->moveToNewParent(
                $item,
                Item::find($request->get('old_parent_id')),
                $old_index,
                $new_parent,
                $new_index
            );
        }
        else {
            $this->itemsRepository->moveItemSameParent(
                $item,
                $old_index,
                $new_index,
                $parent
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
    }

    public function undoDeleteItem()
    {
        $item = Item::where('user_id', Auth::user()->id)
            ->onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->first();

        $item->restore();

        return $item;
    }
}
