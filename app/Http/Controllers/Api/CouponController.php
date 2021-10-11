<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CountryCollection;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductStock;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();
        $date_now = Carbon::now()->toDateTimeString();
        $date_now = strtotime($date_now);
        if($date_now > $coupon->end_date){            
            return response()->json([
                'status' => 'failed', 
                'message' => 'Coupon Expired!',
            ]);
        }
        $price = $request->amount;
        if($coupon->discount_type == 'percent')
        { 
            $price -= ($price*$coupon->discount)/100;
        }else{
            $price -= $coupon->discount;
        }             
        return response()->json([
            'status' => 'success', 
            'amount' => $price
        ]);    

    } 
}
