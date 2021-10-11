<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\SellerPaymentSetting;

class PaymentController extends Controller
{
    public function cashOnDelivery(Request $request)
    {
        $order = new OrderController;
        return $order->processOrder($request);
    }
    public function paymentMode(Request $request)
    {
        $seller_payment = SellerPaymentSetting::where('seller_id', $request->seller_id)->first();
        $paypal = [];
        $razorpay = [];
        $stripe = [];
        $instamojo = [];
        $result = [];
        if($seller_payment){
            if($seller_payment->payment_status == 1){
                $paypal = [
                    'paypal_mid' => $seller_payment->paypal_mid,
                    'paypal_key' => $seller_payment->paypal_key,
                    'paypal_email' => $seller_payment->paypal_email
                ];
            }
            if($seller_payment->razorpay_status == 1){
                $razorpay = [
                    'razorpay_key' => $seller_payment->razorpay_key,
                    'razorpay_secret' => $seller_payment->razorpay_secret
                ];
            }
            if($seller_payment->stripe_status == 1){
                $stripe = [
                    'stripe_key' => $seller_payment->stripe_key,
                    'stripe_secret' => $seller_payment->stripe_secret                    
                ];
            }
            if($seller_payment->instamojo_status == 1){
                $instamojo = [
                    'instamojo_key' => $seller_payment->instamojo_key,
                    'instamojo_token' => $seller_payment->instamojo_token
                ];
            }
            $result = [
                'paypal' => $paypal,
                'razorpay' => $razorpay,
                'stripe' => $stripe,
                'instamojo' => $instamojo
            ];
            return response()->json([
                'status' => 'success',
                'data' => $seller_payment
            ]);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'This Seller is not accepting payment right now!'
            ]);            
        }
    }
}
