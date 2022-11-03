<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ReviewCollection;
use App\Models\Review;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index($id)
    {
        return new ReviewCollection(Review::where('product_id', $id)->latest()->get());
    }

    public function insertProductReview(Request $request)
    {
        $review = new Review;
        $review->product_id = $request->product_id;
        $review->user_id = Auth::user()->id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->viewed = '0';
        if ($review->save()) {
            $product = Product::findOrFail($request->product_id);
            if (count(Review::where('product_id', $product->id)->where('status', 1)->get()) > 0) {
                $product->rating = Review::where('product_id', $product->id)->where('status', 1)->sum('rating') / count(Review::where('product_id', $product->id)->where('status', 1)->get());
            } else {
                $product->rating = 0;
            }
            $product->save();
            $data['success'] = true;
        } else {
            $data['success'] = false;
        }

        return response()->json($data);
    }
}
