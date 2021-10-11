<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ReviewCollection;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index($id)
    {
        return new ReviewCollection(Review::where('product_id', $id)->latest()->get());
    }
    public function reviewSave(Request $request)
    {
        $is_review = Review::where('user_id', $request->user_id)->where('product_id', $request->product_id)->first();
        if($is_review){
            $review = Review::find($is_review->id);
            $review->product_id = $request->product_id;
            $review->user_id = $request->user_id;
            $review->rating_delivery = isset($request->rating_delivery) ? $request->rating_delivery : '';
            $review->rating_price = isset($request->rating_price) ? $request->rating_price : '';
            $review->rating_value = isset($request->rating_value) ? $request->rating_value : '';
            $review->rating_quality = isset($request->rating_quality) ? $request->rating_quality : '';
            $review->comment = isset($request->comment) ? $request->comment : '';
            $review->viewed = '0';
        }else{
            $review = new Review;
            $review->product_id = $request->product_id;
            $review->user_id = $request->user_id;
            $review->rating_delivery = isset($request->rating_delivery) ? $request->rating_delivery : '';
            $review->rating_price = isset($request->rating_price) ? $request->rating_price : '';
            $review->rating_value = isset($request->rating_value) ? $request->rating_value : '';
            $review->rating_quality = isset($request->rating_quality) ? $request->rating_quality : '';
            $review->comment = isset($request->comment) ? $request->comment : '';
            $review->viewed = '0';
        }
        if($review->save()){
            $product = Product::findOrFail($request->product_id);
            if(count(Review::where('product_id', $product->id)->where('status', 1)->get()) > 0){
                $product->rating = Review::where('product_id', $product->id)->where('status', 1)->sum('rating_delivery', 'rating_price', 'rating_value', 'rating_quality')/count(Review::where('product_id', $product->id)->where('status', 1)->get());
            }
            else {
                $product->rating = 0;
            }
            $product->save();
            return response()->json([
                'product_id' => $product->id,
                'review_id' => $review->id,
                'status' => 'success',
                'message' => 'Review has been submitted successfully',
            ]);
        }
        return response()->json([
            'status' => 'failed',
            'message' => 'Review has been submitted successfully',
        ]);
    }
}
