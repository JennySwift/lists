<?php

namespace App\Http\Controllers;

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

class ListsController extends Controller
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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function pageLoad()
    {
        $items = Item::whereNull('parent_id')
            ->orderBy('index', 'asc')
            ->get();

        JavaScript::put([
            'items' => $items,
            'categories' => $this->categoriesRepository->getCategories(),
            'base_path' => base_path()
        ]);

        return view('lists');

//        return view('lists', compact('items'));
    }

    public function index()
    {
        $items = Item::whereNull('parent_id')
            ->orderBy('index', 'asc')
            ->get();
        return $items;
    }

    public function filter(Request $request)
    {
        $typing = '%' . $request->get('typing') . '%';

        $items = Item::where('title', 'LIKE', $typing)
//            ->whereIn('id', $item->descendants)
            ->get();

        return $items;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
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

        //If the user clicked on 'home'
//        if (!$item) {
//            return Item::whereNull('parent_id')->get();
//        }

        return [
            //Doing ->get() so that the children don't end up
            //in the breadcrumb unnecessarily.
            'children' => $item->children()->get(),
            'breadcrumb' => $item->breadcrumb()
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
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
        $item->save();
//        Debugbar::info('category_id: ' . $request->get('category_id'));
//        Debugbar::info('item', $item);
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
//        return $this->showSomething($item->parent_id);
    }
}
