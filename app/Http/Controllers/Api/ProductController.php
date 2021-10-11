<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ShopCollection;
use App\Http\Resources\ProductDetailCollection;
use App\Http\Resources\SearchProductCollection;
use App\Http\Resources\FlashDealCollection;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Color;
use DB;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return new ProductCollection(Product::latest()->paginate(10));
    }

    public function show($id)
    {
        return new ProductDetailCollection(Product::where('id', $id)->get());
    }

    public function admin()
    {
        return new ProductCollection(Product::where('added_by', 'admin')->latest()->paginate(10));
    }

    public function seller()
    {
        return new ProductCollection(Product::where('added_by', 'seller')->latest()->paginate(10));
    }

    public function category($id)
    {
        return new ProductCollection(Product::where('category_id', $id)->latest()->paginate(10));
    }

    public function subCategory($id)
    {
        return new ProductCollection(Product::where('subcategory_id', $id)->latest()->paginate(10));
    }

    public function subSubCategory($id)
    {
        return new ProductCollection(Product::where('subsubcategory_id', $id)->latest()->paginate(10));
    }

    public function brand($id)
    {
        return new ProductCollection(Product::where('brand_id', $id)->latest()->paginate(10));
    }

    public function todaysDeal()
    {
        return new ProductCollection(Product::where('todays_deal', 1)->latest()->get());
    }

    public function flashDeal()
    {
        $flash_deals = FlashDeal::where('status', 1)->where('featured', 1)->where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->get();
        return new FlashDealCollection($flash_deals);
    }

    public function featured()
    {
        return new ProductCollection(Product::where('featured', 1)->latest()->get());
    }

    public function featuredServices()
    {
        return new ProductCollection(Product::where('featured', 1)->where('digital', 1)->latest()->get());
    }

    public function bestSeller()
    {
        $products = Product::orderBy('num_of_sale', 'desc')->take(10)->get();
        $shops_array = [];
        foreach($products as $product){
            $shops = Shop::where('user_id', $product->user_id)->first();    
            array_push($shops_array, $shops);
        }
        return new ShopCollection($shops_array);
    }
    public function bestSelling()
    {
        return new ProductCollection(Product::orderBy('num_of_sale', 'desc')->limit(20)->get());
    }

    public function related($id)
    {
        $product = Product::find($id);
        return new ProductCollection(Product::where('subcategory_id', $product->subcategory_id)->where('id', '!=', $id)->limit(10)->get());
    }

    public function topFromSeller($id)
    {
        $product = Product::find($id);
        return new ProductCollection(Product::where('user_id', $product->user_id)->orderBy('num_of_sale', 'desc')->limit(4)->get());
    }

    public function search()
    {
        $key = request('key');
        $scope = request('scope');

        switch ($scope) {

            case 'price_low_to_high':
                $collection = new SearchProductCollection(Product::where('name', 'like', "%{$key}%")->orWhere('tags', 'like', "%{$key}%")->orderBy('unit_price', 'asc')->paginate(10));
                $collection->appends(['key' =>  $key, 'scope' => $scope]);
                return $collection;

            case 'price_high_to_low':
                $collection = new SearchProductCollection(Product::where('name', 'like', "%{$key}%")->orWhere('tags', 'like', "%{$key}%")->orderBy('unit_price', 'desc')->paginate(10));
                $collection->appends(['key' =>  $key, 'scope' => $scope]);
                return $collection;

            case 'new_arrival':
                $collection = new SearchProductCollection(Product::where('name', 'like', "%{$key}%")->orWhere('tags', 'like', "%{$key}%")->orderBy('created_at', 'desc')->paginate(10));
                $collection->appends(['key' =>  $key, 'scope' => $scope]);
                return $collection;

            case 'popularity':
                $collection = new SearchProductCollection(Product::where('name', 'like', "%{$key}%")->orWhere('tags', 'like', "%{$key}%")->orderBy('num_of_sale', 'desc')->paginate(10));
                $collection->appends(['key' =>  $key, 'scope' => $scope]);
                return $collection;

            case 'top_rated':
                $collection = new SearchProductCollection(Product::where('name', 'like', "%{$key}%")->orWhere('tags', 'like', "%{$key}%")->orderBy('rating', 'desc')->paginate(10));
                $collection->appends(['key' =>  $key, 'scope' => $scope]);
                return $collection;

            // case 'category':
            //
            //     $categories = Category::select('id')->where('name', 'like', "%{$key}%")->get()->toArray();
            //     $collection = new SearchProductCollection(Product::where('category_id', $categories)->orderBy('num_of_sale', 'desc')->paginate(10));
            //     $collection->appends(['key' =>  $key, 'scope' => $scope]);
            //     return $collection;
            //
            // case 'brand':
            //
            //     $brands = Brand::select('id')->where('name', 'like', "%{$key}%")->get()->toArray();
            //     $collection = new SearchProductCollection(Product::where('brand_id', $brands)->orderBy('num_of_sale', 'desc')->paginate(10));
            //     $collection->appends(['key' =>  $key, 'scope' => $scope]);
            //     return $collection;
            //
            // case 'shop':
            //
            //     $shops = Shop::select('user_id')->where('name', 'like', "%{$key}%")->get()->toArray();
            //     $collection = new SearchProductCollection(Product::where('user_id', $shops)->orderBy('num_of_sale', 'desc')->paginate(10));
            //     $collection->appends(['key' =>  $key, 'scope' => $scope]);
            //     return $collection;

            default:
                $collection = new SearchProductCollection(Product::where('name', 'like', "%{$key}%")->orWhere('tags', 'like', "%{$key}%")->orderBy('num_of_sale', 'desc')->paginate(10));
                $collection->appends(['key' =>  $key, 'scope' => $scope]);
                return $collection;
        }
    }

    public function variantPrice(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $str = '';
        $tax = 0;

        if ($request->has('color')) {
            $data['color'] = $request['color'];
            if(isset($request->color)){
                $str = Color::where('name', $request['color'])->first()->name;
            }
        }
        if ($request->has('variant')) {
            $str = $str."".$request->variant;
        }
        $str = str_split($str);
        sort($str);
        $str = implode("", $str);
        if($str != null && $product->variant_product){
            $product_stock = $product->stocks->where('variant', $str)->first();
            if($product_stock){
                $price = $product_stock->price;
                $photos = $product_stock->variant_img;
                $price_usd = $product_stock->price_usd;
                $stockQuantity = $product_stock->qty;
            }else{
                $price = $product->unit_price;
                $price_usd = $product->price_usd;
                $photos = $product->photos;
                $stockQuantity = $product->current_stock;
            }
        }
        else{
            $price = $product->unit_price;
            $price_usd = $product->price_usd;
            $photos = $product->photos;
            $stockQuantity = $product->current_stock;
        }
        //discount calculation
        $flash_deals = FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $key => $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1 && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
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
            if($product->discount_type == 'amount_usd'){
                $price_usd -= $product->discount;
            }elseif($product->discount_type == 'amount_inr'){
                $price -= $product->discount;
            }else{
                $price -= ($price*$product->discount)/100;
                $price_usd -= ($price*$product->discount)/100;
            }
        }
        return response()->json([
            'product_id' => $product->id,
            'variant' => $str,
            'photos' => json_decode($photos),
            'price' => $price,
            'price_usd' => $price_usd,
            'in_stock' => $stockQuantity 
        ]);
    }

    public function home()
    {
        return new ProductCollection(Product::inRandomOrder()->take(50)->get());
    }
}
