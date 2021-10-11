<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\BusinessSetting;
use App\User;
use DB;

class CheckoutController extends Controller
{
    public function checkout_done($inputs, $payment){
        $order = Order::findOrFail($inputs['order_id']);
        $order->payment_status = 'paid';
        $order->payment_details = $payment;
        if($order->save()){
            $is_order_detail = \App\OrderDetail::where('order_id', $inputs['order_id'])->first();
            $order_details = \App\OrderDetail::findOrFail($is_order_detail->id);
            $order_details->payment_status = 'paid';
            $order_details->save();
            return response()->json([
                'success' => true,
                'message' => 'The payment has been completed successfully!'
            ]);
        }
    }
}