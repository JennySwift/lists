<?php

namespace App\Http\Controllers\Lists;

use App\Models\Item;
use Illuminate\Http\Request;
use JavaScript;
use Auth;
use Debugbar;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ListsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
        $parent_id = $request->get('parent_id');
        $new_item = $request->get('new_item');
        Debugbar::info('request', $request->all());

        $item = new Item([
            'title' => $new_item['title'],
            'body' => $new_item['body']
        ]);

        if ($parent_id) {
            $item->parent_id = $parent_id;
        }

        $item->user()->associate(Auth::user());
        $item->save();

        return $this->showSomething($parent_id);
    }

    public function showSomething($parent_id)
    {
        if ($parent_id) {
            return $this->show($parent_id);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $old_index = $request->get('old_index');
        $new_index = $request->get('new_index');
        $parent_id = $request->get('parent_id');

        //Update the siblings
        if ($new_index > $old_index) {

        }
        else if ($new_index < $old_index) {
            Item::where('parent_id', $parent_id)
                ->where('index', '>=', $new_index)
                ->where('index', '<', $old_index)
                ->increment('index');
//            if ($parent_id) {
//                Item::where('parent_id', $parent_id)
//                    ->where('index', '>=', $new_index)
//                    ->increment('index');
//            }
//            else {
//                Item::whereNull('parent_id')
//                    ->where('index', '>=', $new_index)
//                    ->increment('index');
//            }

        }

        //Update the item
        $item = Item::find($id);
        $item->index = $new_index;
        $item->save();
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
