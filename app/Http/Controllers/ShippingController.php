<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Seller;
use App\User;
use App\Currency;
use App\SellerAccountTypeMapping;
use App\Shop;
use App\AccountType;
use App\ShippingSetup;
use App\Product;
use App\Order;
use App\OrderDetail;
use Illuminate\Support\Facades\Hash;

class ShippingController extends Controller
{
    public function getShippingSetup(Request $request){
        $inputs = $request->all();
        //retriving the currency price from session 
        $code = \Session::get('currency_code');
        if($code == 'Rupee'){
            $country = 'india';            
        }else{
            $country = 'USA';
        }
        $seller_id = Auth::user()->seller->id;
        $shipping = ShippingSetup::where('seller_id',$seller_id)->get();    
        if(!empty($shipping)){
            return view('frontend.seller.shipping_setup', compact('shipping', 'country'));
        }
        return view('frontend.seller.shipping_setup');
    }
    
    public function postShippingSetup(Request $request) {

        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }

        
        //retriving the currency price from session 
        $currency = Currency::where('code', 'Rupee')->first();
        $exchange_rate = $currency->exchange_rate;

        $response['status'] = 0;
        $response['status'] = 'Something went wrong!';
        $inputs = $request->all();
        $seller_id = Auth::user()->seller->id;
        if(!empty($inputs)){
            if($inputs['tab'] == NULL){ 
                $response['stauts'] = 0;
                $response['message'] = 'Please Select Shipping Type';
                return json_encode($response);
            }
            if($inputs['rate_100'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 0-100gm';
                return json_encode($response);
            }
            if($inputs['rate_500'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 101-500gm';
                return json_encode($response);
            }
            if($inputs['rate_1000'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 501-1000gm';
                return json_encode($response);
            }
            if($inputs['rate_1500'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 1001-1500gm';
                return json_encode($response);
            }
            if($inputs['rate_2000'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 1501-2000gm';
                return json_encode($response);
            }
            if($inputs['rate_2500'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 2001-2500gm';
                return json_encode($response);
            }
            if($inputs['rate_3000'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 2501-3000gm';
                return json_encode($response);
            }
            if($inputs['rate_3500'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 3001-3500gm';
                return json_encode($response);
            }
            if($inputs['rate_4000'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 3501-4000gm';
                return json_encode($response);
            }
            if($inputs['rate_4500'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 4001-4500gm';
                return json_encode($response);
            }
            if($inputs['rate_5000'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 4501-5000gm';
                return json_encode($response);
            }
            if($inputs['more_than_5000'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for more than 5000gm';
                return json_encode($response);
            }
            $shipping_data = ShippingSetup::where('seller_id', $seller_id)
                    ->where('shipping_type', $inputs['tab'])
                    ->first();
            
            if(!empty($shipping_data) && !empty($inputs['tab'])){
                if($shipping_data->shipping_type == $inputs['tab']){
                    $response['stauts'] = 0;
                    $response['message'] = 'Shipping charges for '. $inputs['tab']. ' already existed.';                    
                    return json_encode($response);
                }
            }else{
                $shipping = new ShippingSetup;
                $shipping->seller_id = !empty($seller_id) ? $seller_id : '';
                $shipping->shipping_type = !empty($inputs['tab']) ? $inputs['tab'] : '';
                $rate = [
                    'rate_100' => !empty($inputs['rate_100']) ? bcdiv($inputs['rate_100'], $exchange_rate, 2) : '',
                    'rate_500' => !empty($inputs['rate_500']) ? bcdiv($inputs['rate_500'], $exchange_rate, 2) : '',
                    'rate_1000' => !empty($inputs['rate_1000']) ? bcdiv($inputs['rate_1000'], $exchange_rate, 2) : '',
                    'rate_1500' => !empty($inputs['rate_1500']) ? bcdiv($inputs['rate_1500'], $exchange_rate, 2) : '',
                    'rate_2000' => !empty($inputs['rate_2000']) ? bcdiv($inputs['rate_2000'], $exchange_rate, 2) : '',
                    'rate_2500' => !empty($inputs['rate_2500']) ? bcdiv($inputs['rate_2500'], $exchange_rate, 2) : '',
                    'rate_3000' => !empty($inputs['rate_3000']) ? bcdiv($inputs['rate_3000'], $exchange_rate, 2) : '',
                    'rate_3500' => !empty($inputs['rate_3500']) ? bcdiv($inputs['rate_3500'], $exchange_rate, 2) : '',
                    'rate_4000' => !empty($inputs['rate_4000']) ? bcdiv($inputs['rate_4000'], $exchange_rate, 2) : '',
                    'rate_4500' => !empty($inputs['rate_4500']) ? bcdiv($inputs['rate_4500'], $exchange_rate, 2) : '',
                    'rate_5000' => !empty($inputs['rate_5000']) ? bcdiv($inputs['rate_5000'], $exchange_rate, 2) : '',
                    'more_than_5000' => !empty($inputs['more_than_5000']) ? bcdiv($inputs['more_than_5000'], $exchange_rate, 2) : '',
                ];
                $rate = json_encode($rate);
                $shipping->rate_weight = !empty($rate) ? $rate : '';
                if($shipping->save()){
                    $response['status'] = 1;
                    $response['message'] = 'Shipping Details Saved Successfully.';
                }                
                return json_encode($response);
            }
        }            
    }
    
    public function postShippingUpdate(Request $request) { 
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }

        //retriving the currency price from session 
        $currency = Currency::where('code', \Session::get('currency_code'))->first();
        $exchange_rate = $currency->exchange_rate;

        $response['status'] = 0;
        $response['status'] = 'Something went wrong!';
        $inputs = $request->all();
        $seller_id = Auth::user()->seller->id;
        if(isset($inputs['shipping']) && isset($inputs['rate_array'])){
            $shipping_info = ShippingSetup::where('seller_id', $seller_id)->where('shipping_type', $inputs['shipping'])->first();
            $rate = [
                'rate_100' => !empty($inputs['rate_array']['rate_100']) ? bcdiv($inputs['rate_array']['rate_100'], $exchange_rate, 2) : '',
                'rate_500' => !empty($inputs['rate_array']['rate_500']) ? bcdiv($inputs['rate_array']['rate_500'], $exchange_rate, 2) : '',
                'rate_1000' => !empty($inputs['rate_array']['rate_1000']) ? bcdiv($inputs['rate_array']['rate_1000'], $exchange_rate, 2) : '',
                'rate_1500' => !empty($inputs['rate_array']['rate_1500']) ? bcdiv($inputs['rate_array']['rate_1500'], $exchange_rate, 2) : '',
                'rate_2000' => !empty($inputs['rate_array']['rate_2000']) ? bcdiv($inputs['rate_array']['rate_2000'], $exchange_rate, 2) : '',
                'rate_2500' => !empty($inputs['rate_array']['rate_2500']) ? bcdiv($inputs['rate_array']['rate_2500'], $exchange_rate, 2) : '',
                'rate_3000' => !empty($inputs['rate_array']['rate_3000']) ? bcdiv($inputs['rate_array']['rate_3000'], $exchange_rate, 2) : '',
                'rate_3500' => !empty($inputs['rate_array']['rate_3500']) ? bcdiv($inputs['rate_array']['rate_3500'], $exchange_rate, 2) : '',
                'rate_4000' => !empty($inputs['rate_array']['rate_4000']) ? bcdiv($inputs['rate_array']['rate_4000'], $exchange_rate, 2) : '',
                'rate_4500' => !empty($inputs['rate_array']['rate_4500']) ? bcdiv($inputs['rate_array']['rate_4500'], $exchange_rate, 2) : '',
                'rate_5000' => !empty($inputs['rate_array']['rate_5000']) ? bcdiv($inputs['rate_array']['rate_5000'], $exchange_rate, 2) : '',
                'more_than_5000' => !empty($inputs['rate_array']['more_than_5000']) ? bcdiv($inputs['rate_array']['more_than_5000'], $exchange_rate, 2) : '',
            ];
            $rate = json_encode($rate);
            $shipping_info->rate_weight = !empty($rate) ? $rate : '';
            $shipping_info->save();
            $response['status'] = 1;
            $response['message'] = 'Shipping Details Updated Successfully.';
            return json_encode($response);
        }  
    }
}
