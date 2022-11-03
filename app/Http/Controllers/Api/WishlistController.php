<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\WishlistCollection;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{

    public function index($id)
    {
        return new WishlistCollection(Wishlist::where('user_id', auth()->id())->latest()->get());
    }

    public function store(Request $request)
    {
        Wishlist::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $request->product_id]
        );
        return response()->json(['success' => true, 'message' => trans('messages.Product is successfully added to your wishlist')], 201);
    }

    public function storeFav(Request $request)
    {
        Wishlist::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $request->product_id]
        );
        return response()->json(['success' => true, 'message' => trans('messages.Product is successfully added to your wishlist')], 201);
    }

    public function destroyfromFav($id)
    {
        $wish = Wishlist::where(['user_id' => auth()->id(), 'product_id' => $id])->first();
        if ($wish != null) {
           $wish->delete();
           return response()->json(['success' => true, 'message' => trans('messages.Product is successfully removed from your wishlist')], 200);
        }else{
            return response()->json(['success' => false, 'message' => trans('messages.Product already deleted')], 200);
        }
    }

    public function isProductInWishlist(Request $request)
    {
        $product = Wishlist::where(['success' => true, 'product_id' => $request->product_id, 'user_id' => $request->user_id])->count();
        if ($product > 0)
            return response()->json([
                'message' => trans('messages.Product present in wishlist'),
                'is_in_wishlist' => true,
                'product_id' => (int) $request->product_id,
                'wishlist_id' => (int) Wishlist::where(['product_id' => $request->product_id, 'user_id' => $request->user_id])->first()->id
            ], 200);

        return response()->json([
            'message' => trans('messages.Product is not present in wishlist'),
            'is_in_wishlist' => false,
            'product_id' => (int) $request->product_id,
            'wishlist_id' => 0
        ], 200);
    }
}
