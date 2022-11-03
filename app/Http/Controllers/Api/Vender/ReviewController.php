<?php

namespace App\Http\Controllers\Api\Vender;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Review;
use App\Product;
use Auth;
use DB;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = DB::table('reviews')
                    ->orderBy('id', 'desc')
                    ->join('products', 'reviews.product_id', '=', 'products.id')
                    ->where('products.user_id', auth('api')->user()->id)
                    ->select('reviews.*', 'products.*')
                    ->distinct()
                    ->paginate(9);

        foreach ($reviews as $key => $value) {
            $review = \App\Review::find($value->id);
            $review->viewed = 1;
            $review->save();
        }
        return response()->json([
            'reviews' => $reviews
        ]);
    }
}
