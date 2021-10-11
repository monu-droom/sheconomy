<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CartCollection;
use App\Models\Cart;
use App\Seller;
use App\ShippingSetup;
use App\Models\Color;
use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index($id)
    {
        return new CartCollection(Cart::where('user_id', $id)->latest()->get());
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $seller = \App\Seller::where('user_id', $product->user_id)->first();

        $variant = $request->variant;
        $color = $request->color;
        $tax = 0;

        if ($variant == '' && $color == '')
            $price = $product->unit_price;
        else {
            //$variations = json_decode($product->variations);
            $product_stock = $product->stocks->where('variant', $variant)->first();
            $price = $product_stock->price;
        }

        //discount calculation based on flash deal and regular discount
        //calculation of taxes
        $flash_deals = FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1  && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
                $flash_deal_product = FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
                if($flash_deal_product->discount_type == 'percent'){
                    $price -= ($price*$flash_deal_product->discount)/100;
                }
                elseif($flash_deal_product->discount_type == 'amount'){
                    $price -= $flash_deal_product->discount;
                }
                $inFlashDeal = true;
                break;
            }
        }
        if (!$inFlashDeal) {
            if($product->discount_type == 'percent'){
                $price -= ($price*$product->discount)/100;
            }
            elseif($product->discount_type == 'amount'){
                $price -= $product->discount;
            }
        }

        if ($product->tax_type == 'percent') {
            $tax = ($price * $product->tax) / 100;
        }
        elseif ($product->tax_type == 'amount') {
            $tax = $product->tax;
        }
        //finding the seller and users data for shiping
        $user = \App\Address::where('user_id', $request->user_id)->first();
        $shop = \App\Shop::where('user_id', $product->user_id)->first();

        if(strtolower(!isset($user->country)) == strtolower(!isset($shop->country))){
            $seller = Seller::where('user_id', $shop->user_id)->first();
            $shipping_charge = ShippingSetup::where('seller_id', $seller->id)
                        ->where('shipping_type', 'national')
                        ->first();
            if(strtolower($user->state) == strtolower($shop->state) && strtolower($user->city) != strtolower($shop->city)){
                $seller = Seller::where('user_id', $shop->user_id)->first();
                $shipping_charge = ShippingSetup::where('seller_id', $seller->id)
                        ->where('shipping_type', 'regional') 
                        ->first();                
            }
            if(strtolower($user->city) == strtolower($shop->city) && strtolower($user->state) != strtolower($shop->state)){
                $seller = Seller::where('user_id', $shop->user_id)->first();
                $shipping_charge = ShippingSetup::where('seller_id', $seller->id)
                        ->where('shipping_type', 'local')
                        ->first();
            }
            if(strtolower($user->city) == strtolower($shop->city) && strtolower($user->state) == strtolower($shop->state)){
                $seller = Seller::where('user_id', $shop->user_id)->first();
                $shipping_charge = ShippingSetup::where('seller_id', $seller->id)
                        ->where('shipping_type', 'local')
                        ->first();
            }
        }else{     
                $seller = Seller::where('user_id', $shop->user_id)->first();
                $shipping_charge = ShippingSetup::where('seller_id', $seller->id)
                        ->where('shipping_type', 'international')
                        ->first();
        }
        $weight_array = [
            "rate_100",
            "rate_500",
            "rate_1000",
            "rate_1500",
            "rate_2000",
            "rate_2500",
            "rate_3000",
            "rate_3500",
            "rate_4000",
            "rate_4500",
            "rate_5000"
        ];
        if(!empty($shipping_charge->rate_weight)){
            $shipping_price = json_decode($shipping_charge->rate_weight, true);
            if(in_array($product->weight, $weight_array)){
                $shipping_price = $shipping_price[$product->weight];
            }else{
                flash(__('Product weight for this product is not mentioned yet!'))->warning();
                return redirect()->route('home');                
            }
        }else{            
            flash(__('Shipping cost for this product is not mentioned yet!'))->warning();
            return redirect()->route('home');
        }
        //free shipping 
        if(!empty($product) && $product->shipping_type == 'free'){
            $seller = Shop::where('user_id', $product->user_id)->first();
            $geo_location = Session::get('location');
            if(!empty($user)){
                if(strtolower($user->city) == strtolower($seller->city)){
                    $shipping_price = 0;                
                }
            }else{
                if(strtolower($geo_location->cityName) == strtolower($seller->city)){
                    $shipping_price = 0;                
                }
            }
        }
        if($shipping_price == ''){
            $shipping_price = 0;
        }
        Cart::updateOrCreate([
            'user_id' => $request->user_id,
            'seller_id' => $seller->id,
            'product_id' => $request->id,
            'variation' => $variant
        ], [
            'price' => $price,
            'tax' => $tax,
            'shipping_cost' => $shipping_price,
            'quantity' => DB::raw('quantity + 1')
        ]);

        return response()->json([
            'message' => 'Product added to cart successfully'
        ]);
    }

    public function changeQuantity(Request $request)
    {
        $cart = Cart::findOrFail($request->id);
        $cart->update([
            'quantity' => $request->quantity
        ]);
        return response()->json(['message' => 'Cart updated'], 200);
    }

    public function destroy($id)
    {
        Cart::destroy($id);
        return response()->json(['message' => 'Product is successfully removed from your cart'], 200);
    }
    public function cartSummary(Request $request){
        $cart = Cart::where('user_id', $request->user_id)->where('seller_id', $request->seller_id)->first();
        $product = Product::where('id',$cart->product_id)->first();
        $price = $request->amount;
        if($product->discount_type == 'percent'){
            $discount = ($price*$product->discount)/100;
        }else{
            $discount = $price - $product->discount;
        }
        return response()->json([
            'sub_total' => $cart->price,
            'tax' => $cart->tax,
            'shipping_cost' => $cart->shipping_cost,
            'discount' => $discount,
            'grand_total' => $cart->price + $cart->shipping_cost,
            'coupon_code' => '',
            'coupon_applied' =>'',
        ]);
    }
}
