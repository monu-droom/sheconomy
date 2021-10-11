<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\ProductStock;
use App\Category;
use App\Language;
use App\Currency;
use Auth;
use App\SubSubCategory;
use Session;
use ImageOptimizer;
use DB;
use CoreComponentRepository;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_products(Request $request)
    {
        //CoreComponentRepository::instantiateShopRepository();

        $type = 'In House';
        $col_name = null;
        $query = null;
        $sort_search = null;

        $products = Product::where('added_by', 'admin');

        if ($request->type != null){
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null){
            $products = $products
                        ->where('name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }

        $products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate(15);

        return view('products.index', compact('products','type', 'col_name', 'query', 'sort_search'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function seller_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::where('added_by', 'seller');
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null){
            $products = $products
                        ->where('name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }
        if ($request->type != null){
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }

        $products = $products->orderBy('created_at', 'desc')->paginate(15);
        $type = 'Seller';

        return view('products.index', compact('products','type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $messages = [
            'thumbnail_img.dimensions' => 'Thumbnail image must be 200 x 319',
            'photos.dimensions' => 'Main image must be or greater than 1100px'
        ];
        $this->validate($request, [
            'thumbnail_img' => 'required|dimensions:max_width=200,max_height=319',
            'photos' => 'required',
        ], $messages);

        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
        
        $product = new Product;
        if(!empty($request->country)){
            $product->country = $request->country;            
        }else{
            flash(translate('Kindly select the country!'))->error();                        
            return back();
        }
        if(!empty($request->weight)){
            $product->weight = $request->weight;            
        }else{
            flash(translate('Kindly select the weight of product!'))->error();                        
            return back();
        }
        if($request->refundable == 'on'){
            $product->return_validity = isset($request->return) ? $request->return : 0;
        }else{
            $product->return_validity = 0;
        }
        $product->name = $request->name;
        $product->product_sku = $request->product_sku;
        $product->added_by = $request->added_by;
        if(Auth::user()->user_type == 'seller'){
            $product->user_id = Auth::user()->id;
        }
        else{
            $product->user_id = \App\User::where('user_type', 'admin')->first()->id;
        }
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->subsubcategory_id = $request->subsubcategory_id;
        $product->brand_id = $request->brand_id;
        $product->current_stock = $request->current_stock;
        $product->barcode = $request->barcode;

        if ($refund_request_addon != null && $refund_request_addon->activated == 1) {
            if ($request->refundable != null) {
                $product->refundable = 1;
            }
            else {
                $product->refundable = 0;
            }
        }

        $photos = array();

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads/products/photos');
                array_push($photos, $path);
                //ImageOptimizer::optimize(base_path('public/').$path);
            }
            $product->photos = json_encode($photos);
        }

        if($request->hasFile('thumbnail_img')){
            $product->thumbnail_img = $request->thumbnail_img->store('uploads/products/thumbnail');
            //ImageOptimizer::optimize(base_path('public/').$product->thumbnail_img);
        }

        $product->unit = $request->unit;
        $product->min_qty = $request->min_qty;
        $product->tags = implode('|',$request->tags);
        $product->description = $request->description;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        //retriving the currency price from session 
        $currency = Currency::where('code', 'Rupee')->first();
        $exchange_rate = $currency->exchange_rate;
        $product->unit_price = $request->unit_price_ei;
        $product->price_usd = $request->unit_price_nei;
        // $product->purchase_price = $request->purchase_price;
        $product->tax_type = $request->tax_type;
        if($request->tax_type == 'amount_usd'){
            $product->tax = $request->tax;
        }elseif($request->tax_type == 'amount_inr'){
            $product->tax = $request->tax;
        }else{
            $product->tax = $request->tax;
        }
        $product->discount_type = $request->discount_type;
        if($request->discount_type == 'amount_usd'){
            $product->discount = $request->discount;
        }elseif($request->discount_type == 'amount_inr'){
            $product->discount = $request->discount;
        }else{
            $product->discount = $request->discount;
        }
        $inputs = $request->all();
        if(!empty($inputs) && isset($inputs['free_shipping']) && $inputs['free_shipping'] == 'on'){
            $product->shipping_type = 'free';
        }
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;

        if($request->hasFile('meta_img')){
            $product->meta_img = $request->meta_img->store('uploads/products/meta');
            //ImageOptimizer::optimize(base_path('public/').$product->meta_img);
        } else {
            $product->meta_img = $product->thumbnail_img;
        }

        if($product->meta_title == null) {
            $product->meta_title = $product->name;
        }

        if($product->meta_description == null) {
            $product->meta_description = $product->description;
        }

        if($request->hasFile('pdf')){
            $product->pdf = $request->pdf->store('uploads/products/pdf');
        }

        $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $product->colors = json_encode($request->colors);
        }
        else {
            $colors = array();
            $product->colors = json_encode($colors);
        }
        //UPDATING REQUEST DATA
        $choice_options = array();
        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;
                $my_str = $request[$str];
                foreach($request->$str as $st){
                    $searchForValue = ',';
                    if( strpos($st, $searchForValue) !== false ) {                        
                        $string = explode(',', $st);
                        $str = $string;
                    }else{
                        $str = $my_str;
                    }
                }
                $item['attribute_id'] = $no;
                $item['values'] = $str;
                array_push($choice_options, $item);
            }
        }
        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        }
        else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options);

        //$variations = array();
        $product->save();
        //combinations start
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = $request[$name];
                foreach($request->$name as $st){
                    $searchForValue = ',';
                    if( strpos($st, $searchForValue) !== false ) {                        
                        $string = explode(',', $st);
                        $str = $string;
                    }else{
                        $str = $my_str;
                    }
                }
                // array_push($options, explode(',', $my_str));
                array_push($options, $str);
            }
        }
        //Generates the combinations of customer choice options
        $combinations = combinations($options);
        if(count($combinations[0]) > 0){
            $product->variant_product = 1;
            foreach ($combinations as $key => $combination){
                $str = '';
                $vrnt = '';
                foreach ($combination as $key => $item){
                    if($key > 0 ){
                        $str .= '-'.str_replace(' ', '', $item);
                        $vrnt .= ''.str_replace(' ', '', $item);
                    }
                    else{
                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                            $color_name = \App\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                            $vrnt .= $color_name;
                        }
                        else{
                            $str .= str_replace(' ', '', $item);
                            $vrnt .= str_replace(' ', '', $item);
                        }
                    }
                }

                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                if($product_stock == null){
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $product->id;
                }
                $variant_img = array();
                if($request->hasFile($str)){
                    unset($variant_img);
                    $variant_img = array();
                    foreach($request->$str as $key => $img) {
                        $path = $img->store('uploads/products/variants/');
                        array_push($variant_img, $path);
                        $product_stock->variant_img = json_encode($variant_img);
                        if(is_array($vrnt)){
                            sort($vrnt);    
                        }else{
                            $vrnt = str_split($vrnt);
                            sort($vrnt);
                        }
                        $product_stock->variant = implode('', $vrnt);
                        $product_stock->price = $request['price_'.str_replace('.', '_', $str)];
                        $product_stock->price_usd = $request['price_'.str_replace('.', '_', $str).'_usd'];
                        $product_stock->sku = $request['sku_'.str_replace('.', '_', $str)];
                        $product_stock->qty = $request['qty_'.str_replace('.', '_', $str)];
                    }
                    $product_stock->save();
                }
            }
        }
        //combinations end
        foreach (Language::all() as $key => $language) {
            $data = openJSONFile($language->code);
            $data[$product->name] = $product->name;
            saveJSONFile($language->code, $data);
        }
	    $product->save();
        flash(translate('Product has been inserted successfully'))->success();
        if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){
            return redirect()->route('products.admin');
        }
        else{
            if(\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
                $seller = Auth::user()->seller;
                $seller->remaining_uploads -= 1;
                $seller->save();
            }
            return redirect()->route('seller.products');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function admin_product_edit($id)
    {
        $product = Product::findOrFail(decrypt($id));
        $tags = json_decode($product->tags);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories', 'tags'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function seller_product_edit($id)
    {
        $product = Product::findOrFail(decrypt($id));
        $tags = json_decode($product->tags);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = [
            'thumbnail_img.dimensions' => 'Thumbnail image must be 290 x 300',
        ];
        $this->validate($request, [
            'thumbnail_img' => 'dimensions:max_width=290,max_height=300',
        ], $messages);

        if(isset($request->choice_attributes) && isset($request->choice_no)){
            foreach($request->choice_attributes as $choice_attr){
                $choice_no = 'choice_options_'.$choice_attr;
                foreach($request->$choice_no as $ch_no){
                    if($ch_no == null && $choice_attr != null){
                        flash(translate('Kindly Fill Attribute Options!'))->error();                        
                        return back();                        
                    }
                }
            }
        }
        if(!isset($request->colors) && !isset($request->choice_attributes)){
            $is_product_stocks = ProductStock::where('product_id', $id)->get();
            foreach($is_product_stocks as $is_product_stock){
                $delete_stock = ProductStock::findOrFail($is_product_stock->id);
                $delete_stock->delete();
            }
            $is_product = Product::findOrFail($id);
            $is_product->variant_product = 0;
            $is_product->save();
        }

        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
        $product = Product::findOrFail($id);
        if(!empty($request->country)){
            $product->country = $request->country;            
        }else{
            flash(translate('Kindly select the country!'))->error();                        
            return back();
        }
        if(!empty($request->weight)){
            $product->weight = $request->weight;            
        }else{
            flash(translate('Kindly select the weight of product!'))->error();                        
            return back();
        }
        if($request->refundable == 'on'){
            $product->return_validity = isset($request->return) ? $request->return : 0;
        }else{
            $product->return_validity = 0;
        }
        $product->name = $request->name;
        $product->product_sku = $request->product_sku;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->subsubcategory_id = $request->subsubcategory_id;
        $product->brand_id = $request->brand_id;
        $product->current_stock = $request->current_stock;
        $product->barcode = $request->barcode;

        if ($refund_request_addon != null && $refund_request_addon->activated == 1) {
            if ($request->refundable != null) {
                $product->refundable = 1;
            }
            else {
                $product->refundable = 0;
            }
        }

        if($request->has('previous_photos')){
            $photos = $request->previous_photos;
        }
        else{
            $photos = array();
        }

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads/products/photos');
                array_push($photos, $path);
                //ImageOptimizer::optimize(base_path('public/').$path);
            }
        }
        $product->photos = json_encode($photos);

        $product->thumbnail_img = $request->previous_thumbnail_img;
        if($request->hasFile('thumbnail_img')){
            $product->thumbnail_img = $request->thumbnail_img->store('uploads/products/thumbnail');
            //ImageOptimizer::optimize(base_path('public/').$product->thumbnail_img);
        }
        $product->unit = $request->unit;
        $product->min_qty = $request->min_qty;
        $product->tags = implode('|',$request->tags);
        $product->description = $request->description;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        //retriving the currency price from session 
        $currency = Currency::where('code', 'Rupee')->first();
        $exchange_rate = $currency->exchange_rate;
        $product->unit_price = $request->unit_price_ei;
        $product->price_usd = $request->unit_price_nei;
        $product->purchase_price = $request->purchase_price;
        $product->tax_type = $request->tax_type;
        if($request->tax_type == 'amount_usd'){
            $product->tax = $request->tax;
        }elseif($request->tax_type == 'amount_inr'){
            $product->tax = $request->tax;
        }else{
            $product->tax = $request->tax;
        }
        $product->discount_type = $request->discount_type;

        if($request->discount_type == 'amount_usd'){
            $product->discount = $request->discount;
        }elseif($request->discount_type == 'amount_inr'){
            $product->discount = $request->discount;
        }else{
            $product->discount = $request->discount;
        }
        $inputs = $request->all();
        if(!empty($inputs) && isset($inputs['free_shipping']) && $inputs['free_shipping'] == 'on'){
            $product->shipping_type = 'free';
        }
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;

        if($request->hasFile('meta_img')){
            $product->meta_img = $request->meta_img->store('uploads/products/meta');
            //ImageOptimizer::optimize(base_path('public/').$product->meta_img);
        } else {
            $product->meta_img = $product->thumbnail_img;
        }

        if($product->meta_title == null) {
            $product->meta_title = $product->name;
        }

        if($product->meta_description == null) {
            $product->meta_description = $product->description;
        }

        if($request->hasFile('pdf')){
            $product->pdf = $request->pdf->store('uploads/products/pdf');
        }

        $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.substr($product->slug, -5);

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $product->colors = json_encode($request->colors);
        }
        else {
            $colors = array();
            $product->colors = json_encode($colors);
        }

        $choice_options = array();
        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;
                $my_str = $request[$str];
                foreach($request->$str as $st){
                    $searchForValue = ',';
                    if( strpos($st, $searchForValue) !== false ) {                        
                        $string = explode(',', $st);
                        $str = $string;
                    }else{
                        $str = $my_str;
                    }
                }
                $item['attribute_id'] = $no;
                $item['values'] = $str;
                array_push($choice_options, $item);
            }
        }
        // if($product->attributes != json_encode($request->choice_attributes)){
        //     foreach ($product->stocks as $key => $stock) {
        //         $stock->delete();
        //     }
        // }

        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        }
        else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options);

        foreach (Language::all() as $key => $language) {
            $data = openJSONFile($language->code);
            unset($data[$product->name]);
            $data[$request->name] = "";
            saveJSONFile($language->code, $data);
        }

        //combinations start
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                // $my_str = implode('',$request[$name]);
                $my_str = $request[$name];
                foreach($request->$name as $na){
                    $searchForValue = ',';
                    if( strpos($na, $searchForValue) !== false ) {                        
                        $string = explode(',', $na);
                        $name = $string;
                    }else{
                        $name = $my_str;
                    }
                }
                // array_push($options, explode(',', $my_str));
                array_push($options, $name);
            }
        }
        $product_stock = ProductStock::where('product_id', $product->id)->get();
        $existing_variant_img = [];
        foreach($product_stock as $stock){
            $product_stock_existed = ProductStock::findOrFail($stock->id);
            $existing_variant_img[$product_stock_existed->variant] = !empty($product_stock_existed->variant_img) ? $product_stock_existed->variant_img : '';
            $product_stock_existed->delete();
        }
        $combinations = combinations($options);
        if(count($combinations[0]) > 0){
            $product->variant_product = 1;
            foreach ($combinations as $key => $combination){
                $str = '';
                $vrnt = '';
                foreach ($combination as $key => $item){
                    if($key > 0 ){
                        $str .= '-'.str_replace(' ', '', $item);
                        $vrnt .= ''.str_replace(' ', '', $item);
                    }
                    else{
                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                            $color_name = \App\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                            $vrnt .= $color_name;
                        }
                        else{
                            $str .= str_replace(' ', '', $item);
                            $vrnt .= str_replace(' ', '', $item);
                        }
                    }
                }

                if(is_array($vrnt)){
                    sort($vrnt);    
                }else{
                    $vrnt = str_split($vrnt);
                    sort($vrnt);
                }
                if(array_key_exists(implode('', $vrnt), $existing_variant_img)){
                    $product_stock = null;    
                }else{
                    $product_stock = 1;
                }
                if($product_stock != null){
                    $product_stock_new = new ProductStock;
                    $product_stock_new->product_id = $product->id;
                }else{
                    $product_stock_new = new ProductStock;
                    $product_stock_new->product_id = $product->id;                    
                }
                $variant_img = array();
                if($request->hasFile($str)){
                    unset($variant_img);
                    $variant_img = array();
                    foreach($request->$str as $key => $img) {
                        $path = $img->store('uploads/products/variants/');
                        array_push($variant_img, $path);
                        $product_stock_new->variant_img = json_encode($variant_img);
                        $product_stock_new->variant = implode('', $vrnt);
                        $product_stock_new->price = $request['price_'.str_replace('.', '_', $str)];
                        $product_stock_new->price_usd = $request['price_'.str_replace('.', '_', $str).'_usd'];
                        $product_stock_new->sku = $request['sku_'.str_replace('.', '_', $str)];
                        $product_stock_new->qty = $request['qty_'.str_replace('.', '_', $str)];
                    }
                    $product_stock_new->save();
                }else{
                    if(array_key_exists(implode('', $vrnt), $existing_variant_img)){
                        $product_stock_new->variant_img = !empty($existing_variant_img[implode('', $vrnt)]) ? $existing_variant_img[implode('', $vrnt)] : null;
                    }
                    $product_stock_new->variant = implode('', $vrnt);
                    $product_stock_new->price = $request['price_'.str_replace('.', '_', implode('', $vrnt))];
                    $product_stock_new->price_usd = $request['price_'.str_replace('.', '_', implode('', $vrnt)).'_usd'];
                    $product_stock_new->sku = $request['sku_'.str_replace('.', '_', implode('', $vrnt))];
                    $product_stock_new->qty = $request['qty_'.str_replace('.', '_', implode('', $vrnt))]; 
                    $product_stock_new->save();
                } 
            }
        }
        $product->save();
        
        flash(translate('Product has been updated successfully'))->success();
        if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){
            return redirect()->route('products.admin');
        }
        else{
            return redirect()->route('seller.products');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if(Product::destroy($id)){
            foreach (Language::all() as $key => $language) {
                $data = openJSONFile($language->code);
                unset($data[$product->name]);
                saveJSONFile($language->code, $data);
            }
            flash(translate('Product has been deleted successfully'))->success();
            if(Auth::user()->user_type == 'admin'){
                return redirect()->route('products.admin');
            }
            else{
                return redirect()->route('seller.products');
            }
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Duplicates the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function duplicate($id)
    {
        $product = Product::find($id);
        $product_new = $product->replicate();
        $product_new->slug = substr($product_new->slug, 0, -5).Str::random(5);

        if($product_new->save()){
            flash(translate('Product has been duplicated successfully'))->success();
            if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){
                return redirect()->route('products.admin');
            }
            else{
                return redirect()->route('seller.products');
            }
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function get_products_by_subcategory(Request $request)
    {
        $products = Product::where('subcategory_id', $request->subcategory_id)->get();
        return $products;
    }

    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }

    public function updateTodaysDeal(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->todays_deal = $request->status;
        if($product->save()){
            return 1;
        }
        return 0;
    }

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;

        if($product->added_by == 'seller' && \App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
            $seller = $product->user->seller;
            if($seller->invalid_at != null && Carbon::now()->diffInDays(Carbon::parse($seller->invalid_at), false) <= 0){
                return 0;
            }
        }

        $product->save();
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->featured = $request->status;
        if($product->save()){
            return 1;
        }
        return 0;
    }

    public function sku_combination(Request $request)
    {
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        else {
            $colors_active = 0;
        }

       $unit_price = $request->unit_price;
       $price_usd = $request->price_usd;
       $product_name = $request->name;

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = $request[$name];
                if($request->has('choice')){
                    foreach($request->choice as $choice){
                        if($choice == 'Unit'){
                            $values = implode(',', array_values($request[$name]));
                            $my_str = explode(',', $values);      
                        }
                    }
                }
                array_push($options, $my_str);
            }
        }
        $combinations = combinations($options);
        return view('partials.sku_combinations', compact('combinations', 'unit_price', 'price_usd', 'colors_active', 'product_name'));
    }

    public function sku_combination_edit(Request $request)
    {
        if(isset($request->colors)){
            $qty_hide = 1;
        }else{
            $qty_hide = 0;
        }
        $product = Product::findOrFail($request->id);
        $product_stocks = ProductStock::where('product_id', $product->id)->get();
        $get_all_variant = ProductStock::where('product_id', $product->id)->get('variant');
        $variants_array = [];
        foreach($get_all_variant as $variant){
            if(!in_array($variant->variant, $variants_array)){
                array_push($variants_array, $variant->variant);
            }
        }
        if(empty(json_decode($product_stocks))){
            $empty_product_stocks = 0;
        }else{
            $empty_product_stocks = 1;
        }
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        else {
            $colors_active = 0;
        }

        $product_name = $request->name;
        $unit_price = $request->unit_price;
        $price_usd = $request->price_usd;
        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = $request[$name];
                
                foreach($request->$name as $na){
                    $searchForValue = ',';
                    if( strpos($na, $searchForValue) !== false ) {                        
                        $string = explode(',', $na);
                        $name = $string;
                    }else{
                        $name = $my_str;
                    }
                }
                array_push($options, $name);
            }
        }
        $combinations = combinations($options);

        return view('partials.sku_combinations_edit', compact('qty_hide', 'combinations', 'empty_product_stocks', 'unit_price', 'price_usd', 'colors_active', 'product_stocks', 'product_name', 'product', 'variants_array'));
    }

    public function makeVariant(Request $request){
        $inputs = $request->all();
        $attr = '';
        $attributes = \App\Attribute::where('name', $inputs['name'])->orderBy('name', 'asc')->get();
        if($attributes){
            foreach($attributes as $attribute){
                if(!empty(json_decode($attribute->sub_attributes))){
                    foreach(json_decode($attribute->sub_attributes) as $attr){
                        $attr = explode(',', $attr);
                    }
                }
            }
        }
        return view('products.make_variant', compact('attr', 'inputs')); 
    }

    public function shareProduct(Request $request){
        $product_info = Product::findOrFail($request->product_id);
        if($product_info->user_id != Auth::id()){
            $inputs = $request->all();
            if($inputs){
                $product_factor = '';
                $is_product_factor = \App\ProductFactor::where('product_id', $inputs['product_id'])->first();
                if($is_product_factor){
                    $product_factor = \App\ProductFactor::findOrFail($is_product_factor->id);
                }
                if($product_factor){
                    $product_factor->share += $inputs['share'];            
                }else{
                    $product_factor = new \App\ProductFactor;
                    $product_factor->product_id = $inputs['product_id'];
                    $product_factor->share += $inputs['share'];
                }
                $product_factor->save(); 
            }
        }
    }
}
