<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\User;
use App\SellerPaymentSetting;
use App\Models\Seller;
use Razorpay\Api\Api;

class RazorpayController extends Controller
{

    public function payWithRazorpay(Request $request)
    {
        $payment_type = $request->payment_type;
        $order_id = $request->order_id;
        $amount = $request->amount;
        $user_id = $request->user_id;

        if(!empty($order_id)){
            $order_detail = OrderDetail::where('order_id', $order_id)->first();
        }else{
            $order_detail = '';
        }
        if(!empty($order_detail)){
            $products = Product::findOrFail($order_detail->product_id);
        }else{
            $products = '';
        }
        if(!empty($products)){
            $seller = Seller::where('user_id', $products->user_id)->first();
        }else{
            $seller = '';
        }
        if(!empty($seller)){
            $seller_payment = SellerPaymentSetting::where('seller_id', $seller->id)->first();
        }else{
            $seller_payment = '';            
        }
        if(!empty($seller_payment) && $seller_payment->razorpay_status == 1){
            $razorpay_key = $seller_payment->razorpay_key;
            $razorpay_secret = $seller_payment->razorpay_secret;
        }else{
            $razorpay_key = '';
            $razorpay_secret = '';
        }
        if ($payment_type == 'cart_payment') {
            $order = Order::find($order_id);
            if(!empty($order)){
                $shipping_address = json_decode($order->shipping_address,true);
            }else{
                $shipping_address = '';
            }
            if(empty($order) || $razorpay_key == '' || $shipping_address ==''){
                $invalid_input = 1;      
            }else{
                $invalid_input = 0;
            }
            return view('frontend.razorpay.order_payment', compact('invalid_input', 'order', 'shipping_address', 'razorpay_key', 'razorpay_secret'));
        } elseif ($payment_type == 'wallet_payment') {
            $user = User::find($user_id);
            return view('frontend.razorpay.wallet_payment',  compact('user', 'amount'));
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

        if (count($input) && !empty($input['razorpay_payment_id'])) {
            $payment_detalis = null;
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
                $payment_details = json_encode(array('id' => $response['id'], 'method' => $response['method'], 'amount' => $response['amount'], 'currency' => $response['currency']));

                return response()->json(['result' => true, 'message' => "Payment Successful", 'payment_details' => $payment_details]);
            } catch (\Exception $e) {
                return response()->json(['result' => false, 'message' => $e->getMessage(), 'payment_details' => '']);
            }
        } else {
            return response()->json(['result' => false, 'message' => 'Payment Failed', 'payment_details' => '']);
        }
    }

    public function success(Request $request)
    {
        try {

            $payment_type = $request->payment_type;

            if ($payment_type == 'cart_payment') {

                checkout_done($request->order_id, $request->payment_details);
            }

            if ($payment_type == 'wallet_payment') {

                wallet_payment_done($request->user_id, $request->amount, 'Razorpay', $request->payment_details);
            }

            return response()->json(['result' => true, 'message' => "Payment is successful"]);


        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()]);
        }
    }

}