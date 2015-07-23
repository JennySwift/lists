<?php

namespace App\Http\Controllers\Lists;

use App\Models\Item;
use Illuminate\Http\Request;
use JavaScript;

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
        $items = Item::whereNull('parent_id')->get();


        JavaScript::put([
            'items' => $items,
            'base_path' => base_path()
        ]);

        return view('lists');

//        return view('lists', compact('items'));
    }

    public function index()
    {
        $items = Item::whereNull('parent_id')->get();
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
    public function store()
    {
        //
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
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
