<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use Redirect;
use App\Order;
use App\Seller;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Input;
use App\CustomerPackage;
use App\SellerPackage;
use App\Http\Controllers\CustomerPackageController;
use Auth;

class RazorpayController extends Controller
{
    public function payWithRazorpay($request)
    {
        if(Session::has('payment_type')){
            if(Session::get('payment_type') == 'cart_payment'){
                $order = Order::findOrFail(Session::get('order_id'));
                $order_details = \App\OrderDetail::where('order_id', $order->id)->first();
                $product = \App\Product::where('id', $order_details->product_id)->first();
                $user = \App\User::where('id', $product->user_id)->first();
                $buyer = Auth::user();
                $seller = \App\Seller::where('user_id', $product->user_id)->first();
                $seller_payment_setting = \App\SellerPaymentSetting::where('seller_id', $seller->id)->first();
                return view('frontend.razor_wallet.order_payment_Razorpay', compact('order', 'seller_payment_setting', 'user', 'buyer'));
            }
            elseif (Session::get('payment_type') == 'wallet_payment') {
                return view('frontend.razor_wallet.wallet_payment_Razorpay');
            }
            elseif (Session::get('payment_type') == 'customer_package_payment') {
                return view('frontend.razor_wallet.customer_package_payment_Razorpay');
            }
            elseif (Session::get('payment_type') == 'seller_package_payment') {
                return view('frontend.razor_wallet.seller_package_payment_Razorpay');
            }
        }

    }

    public function payment(Request $request)
    {
        //Input items of form
        $input = $request->all();
        //get API Configuration
        $api = new Api($input['razorpay_key'], $input['razorpay_secret']);

        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            $payment_detalis = null;
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']));
                $id = $response->id;
                $method = $response->method;
                $amount = $response->amount;
                $currency = $response->currency;
                $ar = [
                    'id' => $id,
                    'method' => $method,
                    'amount' => $amount,
                    'currency' => $currency
                ];
                $payment_detalis = json_encode($ar);
            } catch (\Exception $e) {
                die;
                return  $e->getMessage();
                \Session::put('error',$e->getMessage());
                return redirect()->back();
            }
            // Do something here for store payment details in database...
            if(Session::has('payment_type')){
                if(Session::get('payment_type') == 'cart_payment'){
                    $checkoutController = new CheckoutController;
                    return $checkoutController->checkout_done(Session::get('order_id'), $payment_detalis);
                }
                elseif (Session::get('payment_type') == 'wallet_payment') {
                    $walletController = new WalletController;
                    return $walletController->wallet_payment_done(Session::get('payment_data'), $payment_detalis);
                }
                elseif (Session::get('payment_type') == 'customer_package_payment') {
                    $customer_package_controller = new CustomerPackageController;
                    return $customer_package_controller->purchase_payment_done(Session::get('payment_data'), $payment);
                }
                elseif (Session::get('payment_type') == 'seller_package_payment') {
                    $seller_package_controller = new SellerPackageController;
                    return $seller_package_controller->purchase_payment_done(Session::get('payment_data'), $payment);
                }
            }
        }
    }
}
