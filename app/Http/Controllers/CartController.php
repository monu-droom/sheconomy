<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Product;
use App\Seller;
use App\Shop;
use App\Address;
use App\ShippingSetup;
use App\User;
use App\Cart;
use App\SellerPaymentSetting;
use App\SubSubCategory;
use App\Category;
use Session;
use App\Color;
use Cookie;

class CartController extends Controller
{
    public function index(Request $request)
    {
        //getting the last page url to redirect after the User Login
        Session::put('intended_url',url()->current()); 
        if(Auth::check()){
            $categories = Category::all();
            return view('frontend.view_cart', compact('categories'));
        }else{
            return redirect()->route('user.login');
        }
    }

    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        return view('frontend.partials.addToCart', compact('product'));
    }

    public function updateNavCart(Request $request)
    {
        return view('frontend.partials.cart');
    }

    public function addToCart(Request $request)
    {
        if(Auth::check()){
            $code = session()->get('currency_code');
            $product = Product::find($request->id);
            //checking
            $subtotal = 0;
            if(strtolower($code) == 'rupee'){
                $price = $product->unit_price;
            }else{
                $price = $product->price_usd;            
            }
            $subtotal += $price*$request['quantity'];
            $data = array();
            $data['id'] = $product->id;
            $str = '';
            $tax = 0;
            if($request->quantity < $product->min_qty) {
                return view('frontend.partials.minQtyNotSatisfied', [
                    'min_qty' => $product->min_qty
                ]);
            }


            //check the color enabled or disabled for the product
            if($request->has('color')){
                $data['color'] = $request['color'];
                $str = Color::where('code', $request['color'])->first()->name;
            }

            if ($product->digital != 1) {
                //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
                foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
                    if($str != null){
                        $str .= ''.str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                    }
                    else{
                        $str .= str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                    }
                }
            }
            //for matching the variant
            $str = str_split($str);
            sort($str);
            $str = implode($str);

            $data['variant'] = $str;

            if($str != null && $product->variant_product){
                $product_stock = $product->stocks->where('variant', $str)->first();                            
                if(strtolower($code) == 'rupee'){  
                    $price = $product_stock->price;
                }else{
                    $price = $product_stock->price_usd;
                }
                $quantity = $product_stock->qty;

                if($quantity >= $request['quantity']){
                    // $variations->$str->qty -= $request['quantity'];
                    // $product->variations = json_encode($variations);
                    // $product->save();
                }
                else{
                    return view('frontend.partials.outOfStockCart');
                }
            }
            else{
                if(strtolower($code) == 'rupee'){
                    $price = $product->unit_price;
                }else{
                    $price = $product->price_usd;            
                }
            }
            //discount calculation based on flash deal and regular discount
            //calculation of taxes
            $flash_deals = \App\FlashDeal::where('status', 1)->get();
            $inFlashDeal = false;
            foreach ($flash_deals as $flash_deal) {
                if ($flash_deal != null && $flash_deal->status == 1  && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
                    $flash_deal_product = \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
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
                }else{
                    $price -= $product->discount;
                }
            }
            if($product->tax_type == 'percent'){
                $tax = ($price*$product->tax)/100;
            }else{
                $tax = $product->tax;
            }
            //finding the seller and users data for shiping
            $user = Auth::user();
            $user = Address::where('user_id', $user->id)->first();
            $product = Product::where('id', $request->id)->first();
            $shop = Shop::where('user_id', $product->user_id)->first();

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
            $total = $price + $shipping_price;
            $data['quantity'] = $request['quantity'];
            $data['price'] = $price;
            $data['tax'] = $tax;
            $data['shipping'] = !empty($shipping_price) ? $shipping_price : 0;
            $data['total'] = !empty($total) ? $total : 0;
            $data['product_referral_code'] = null;
            $data['digital'] = $product->digital;
            $data['user_id'] = Auth::id();
            $data['seller_id'] = $seller->id;

            if ($request['quantity'] == null){
                $data['quantity'] = 1;
            }

            if(Cookie::has('referred_product_id') && Cookie::get('referred_product_id') == $product->id) {
                $data['product_referral_code'] = Cookie::get('product_referral_code');
            }
            //-----saving The data in the cart-----
            $cart_data = new Cart;
            $cart_data->user_id = !empty($data['user_id']) ? $data['user_id'] : '';
            $cart_data->seller_id = !empty($data['seller_id']) ? $data['seller_id'] : '';
            $cart_data->product_id = !empty($request->id) ? $request->id : '';
            $cart_data->variation = !empty($data['variant']) ? $data['variant'] : '';
            $cart_data->price = !empty($data['price']) ? $data['price'] : '';
            $cart_data->tax = !empty($data['tax']) ? $data['tax'] : '';
            $cart_data->shipping_cost = !empty($data['shipping']) ? $data['shipping'] : '';
            $cart_data->quantity = !empty($data['quantity']) ? $data['quantity'] : '';
            $cart_data->save();
            //----------------------
            if($request->session()->has('cart')){
                $foundInCart = false;
                $cart = collect();
                
                foreach ($request->session()->get('cart') as $key => $cartItem){
                    if($cartItem['id'] == $request->id){
                        if($cartItem['variant'] == $str){
                            $foundInCart = true;
                            $cartItem['quantity'] += $request['quantity'];
                        }
                    }
                    $cart->push($cartItem);
                }

                if (!$foundInCart) {
                    $cart->push($data);
                }
                $request->session()->put('cart', $cart);
            }
            else{
                $cart = collect([$data]);
                $request->session()->put('cart', $cart);
            }
            return view('frontend.partials.addedToCart', compact('product', 'data'));
        }
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        if(isset($request->product_id)){
            $cart_table = Cart::where('user_id', Auth::id())
                        ->where('product_id', $request->product_id)
                        ->delete();                
        }
        if($request->session()->has('cart')){
            $cart = $request->session()->get('cart', collect([]));
            $cart->forget($request->key);
            $request->session()->put('cart', $cart);
        }
        return 1;
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {  
        $is_carts = \App\Cart::where('user_id', Auth::id())
                    ->where('product_id', $request->product_id)
                    ->first();
        $carts = \App\Cart::findOrFail($is_carts->id);
        if($carts){
            $carts->quantity = $request->quantity;
            $carts->save();
        }   
        return view('frontend.partials.cart_details');
    }
}
