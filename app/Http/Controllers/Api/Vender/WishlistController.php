<?php

namespace App\Http\Controllers\Api\Vender;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Wishlist;
use App\Category;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wishlists = Wishlist::where('user_id', auth('api')->user()->id)->paginate(9);
        return response()->json([
            'wishlists' => $wishlists
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

            $wishlist = Wishlist::where('user_id', $request->user_id)->where('product_id', $request->id)->first();
            if($wishlist == null){
                $wishlist = new Wishlist;
                $wishlist->user_id = $request->user_id;
                $wishlist->product_id = $request->id;
                $wishlist->save();
            }
            return response()->json([
                'wishlist' => $wishlist
            ]);

    }

    public function remove(Request $request)
    {
        $wishlist = Wishlist::find($request->id);
        if($wishlist!=null){
            if(Wishlist::destroy($request->id)){
                return response()->json([
                    'success' => true
                ]);
            }
        }
        return response()->json([
            'error' => 'wishlist doesnot exists'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
