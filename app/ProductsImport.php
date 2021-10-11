<?php

namespace App;

use App\Product;
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use Auth;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $category = \App\Category::where('name', $row['category'])->first();
        $category_id = !empty($category) ? $category->id : '';
        $sub_category = \App\SubCategory::where('name', $row['sub_category'])->first();
        $sub_category_id = !empty($sub_category) ? $sub_category->id : '';
        $sub_sub_category = \App\SubSubCategory::where('name', $row['sub_sub_category'])->first();
        $sub_sub_category_id = !empty($sub_sub_category) ? $sub_sub_category->id : '';
        if(isset($row['brand_name'])){
            $brand = \App\Brand::where('name', $row['brand_name'])->first();
            $brand_id = !empty($brand) ? $brand->id : '';
        }  
        if($row['upload_type'] == 'product'){ 
            $product = new Product;
            $product->name     = $row['name'];
            $sku = str_replace(' ', '-', $row['name']);
            $product->added_by    = Auth::user()->user_type == 'seller' ? 'seller' : 'admin';
            $product->user_id    = Auth::user()->user_type == 'seller' ? Auth::user()->id : User::where('user_type', 'admin')->first()->id;
            $product->category_id    = $category_id;
            $product->subcategory_id    = $sub_category_id;
            $product->subsubcategory_id    = $sub_sub_category_id;
            $product->brand_id    = $brand_id;
            $images[] = $row['main_image'];
            $product->photos = json_encode($images);
            $product->thumbnail_img = $row['thumbnail_image'];
            $product->video_provider    = $row['video_from'];
            $product->video_link    = $row['video_url'];
            $product->tags = $row['search_text'];
            $product->meta_description = $row['meta_description'];
            $product->meta_title = $row['meta_title'];
            $product->meta_img = $row['meta_image'];
            $product->published = 1;
            $product->featured = 0;
            $product->variations = 0;
            $product->shipping_type = $row['free_shipping'] == 'yes' ? 1 : 0;
            $product->current_stock = $row['quantity'];
            $product->unit = $row['product_unit'];
            $product->min_qty = 1;
            $product->discount_type = 'percent';
            $product->discount = $row['discount_in_perc'];
            $product->tax_type = 'percent';
            $product->tax = $row['tax_in_perc'];
            $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $row['name'])).'-'.Str::random(5);
            $product->product_sku = $sku;
            $product->refundable = $row['refundable'] == 'yes' ? 1 : 0; 
            $product->digital = $row['upload_type'] == 'product' ? 0 : 1;
            $product->variant_product = 0;
            $product->weight = $row['product_weight'];
            $product->return_validity = $row['return_validity']; 
             //retriving the currency price from session 
            $code = session()->get('currency_code');
            $currency = Currency::where('code', $code)->first();
            $exchange_rate = $currency->exchange_rate;
            $product->unit_price    = $row['unit_price'] / $exchange_rate;
            $product->pdf = $row['upload_brochure'];
            $product->description = $row['description'];
            $product->country = $row['manufactured_by'];
            $color = \App\Color::where('name', isset($row['color']) ? $row['color'] : '')->first();
            if($color){
                $product->colors = json_encode($color->code);                
            }else{
                $color = [];
                $product->colors = json_encode($color);                
            }
            if($product->save()){
                return $product;
            }
        }
        if($row['upload_type'] == 'service'){
            $service = new Product;
            $service->name     = $row['service_name'];
            $sku = str_replace(' ', '-', $row['service_name']);
            $service->added_by    = Auth::user()->user_type == 'seller' ? 'seller' : 'admin';
            $service->user_id    = Auth::user()->user_type == 'seller' ? Auth::user()->id : User::where('user_type', 'admin')->first()->id;
            $service->category_id    = $category_id;
            $service->subcategory_id    = $sub_category_id;
            $service->subsubcategory_id    = $sub_sub_category_id;
            $images[] = $row['main_image'];
            $service->photos = json_encode($images);
            $service->thumbnail_img = $row['thumbnail_image'];
            $service->video_provider    = $row['video_from'];
            $service->video_link    = $row['video_url'];
            $service->tags = $row['search_text'];
            $service->meta_description = $row['meta_description'];
            $service->meta_title = $row['meta_title'];
            $service->meta_img = $row['meta_image'];
            $service->published = 1;
            $service->featured = 0;
            $service->discount_type = 'percent';
            $service->discount = $row['discount_in_perc'];
            $service->tax_type = 'percent';
            $service->tax = $row['tax_in_perc'];
            $service->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $row['service_name'])).'-'.Str::random(5);
            $service->product_sku = $sku;
            $service->digital = 1;
            $service->variant_product = 0;
            $service->return_validity = isset($row['return_validity']) ? $row['return_validity'] : ''; 
            //retriving the currency price from session 
            $code = session()->get('currency_code');
            $currency = Currency::where('code', $code)->first();
            $exchange_rate = $currency->exchange_rate;
            $service->unit_price    = $row['unit_price'] / $exchange_rate;
            $service->pdf = $row['upload_brochure'];
            $service->description = $row['description'];
            $color = \App\Color::where('name', isset($row['color']) ? $row['color'] : '')->first();
            if($color){
                $service->colors = json_encode($color->code);                
            }else{
                $color = [];
                $service->colors = json_encode($color);                
            }
            if($service->save()){
                return $service;
            }
        }
        if($row['upload_type'] == 'variant'){
            $virtual_sku = Auth::user()->shop->domain.'-'.str_replace(' ', '-', $row['name']);
            $product_new = Product::where('virtual_sku', $virtual_sku)->first();
            if(!$product_new){
                $product = new Product;
                $product->name     = $row['name'];
                $sku = str_replace(' ', '-', $row['name']);
                $product->added_by    = Auth::user()->user_type == 'seller' ? 'seller' : 'admin';
                $product->user_id    = Auth::user()->user_type == 'seller' ? Auth::user()->id : User::where('user_type', 'admin')->first()->id;
                $product->category_id    = $category_id;
                $product->subcategory_id    = $sub_category_id;
                $product->subsubcategory_id    = $sub_sub_category_id;
                $product->brand_id    = $brand_id;
                $images[] = $row['main_image'];
                $product->photos = json_encode($images);
                $product->thumbnail_img = $row['thumbnail_image'];
                $product->video_provider    = $row['video_from'];
                $product->video_link    = $row['video_url'];
                $product->tags = $row['search_text'];
                $product->meta_description = $row['meta_description'];
                $product->meta_title = $row['meta_title'];
                $product->meta_img = $row['meta_image'];
                $product->published = 1;
                $product->featured = 0;
                $product->variations = 0;
                $product->shipping_type = $row['free_shipping'] == 'yes' ? 1 : 0;
                $product->current_stock = $row['quantity'];
                $product->unit = $row['product_unit'];
                $product->min_qty = 1;
                $product->discount_type = 'percent';
                $product->discount = $row['discount_in_perc'];
                $product->tax_type = 'percent';
                $product->tax = $row['tax_in_perc'];
                $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $row['name'])).'-'.Str::random(5);
                $product->product_sku = $sku;
                $product->virtual_sku = Auth::user()->shop->domain.'-'.$sku;
                $product->refundable = $row['refundable'] == 'yes' ? 1 : 0; 
                $product->digital = 0;
                $product->variant_product = 0;
                $product->weight = $row['product_weight'];
                $product->return_validity = $row['return_validity']; 
                $product->unit_price    = $row['unit_price'];
                $product->pdf = isset($row['upload_brochure']) ? $row['upload_brochure'] : '';
                $product->description = isset($row['description']) ? $row['description'] : '';
                $product->country = $row['manufactured_by'];
                $color = \App\Color::where('name', isset($row['color']) ? $row['color'] : '')->first();
                if(!empty($color)){
                    $colors[] = $color->code;
                    $product->colors = json_encode($colors);                
                }else{
                    $color = [];
                    $product->colors = json_encode($color);                
                }
                if($product->save()){
                    $sku = ucfirst($row['color']).ucfirst($row['size']).ucfirst($row['fabric']).$row['unit'].ucfirst($row['flavour']);
                    $ps_sku = str_split(str_replace(',', '',$sku));
                    sort($ps_sku);
                    $ps_sku = implode($ps_sku);
                    $product_stock = new \App\ProductStock;
                    $product_stock->product_id = $product->id;
                    $product_stock->variant = $ps_sku;
                    $product_stock->sku = $sku;
                    $product_stock->price = $row['variant_price'];                
                    $product_stock->qty = $row['variant_quantity'];
                    $variant_images[] = $row['main_image'];     
                    $product_stock->variant_img = json_encode($variant_images);
                    $product_stock->save();
                    return $product;
                }
            }else{
                $sku = ucfirst($row['color']).ucfirst($row['size']).ucfirst($row['fabric']).$row['unit'].ucfirst($row['flavour']);
                $ps_sku = str_split(str_replace(',', '',$sku));
                sort($ps_sku);
                $ps_sku = implode($ps_sku);
                $product_stock = new \App\ProductStock;
                $product_stock->product_id = $product_new->id;
                $product_stock->variant = $ps_sku;
                $product_stock->sku = $sku;
                $product_stock->price = $row['variant_price'];                
                $product_stock->qty = $row['variant_quantity'];                
                $variant_images[] = $row['main_image'];     
                $product_stock->variant_img = json_encode($variant_images);               
                $product_stock->save();
                return $product_new;
            }
        }
    }

    public function rules(): array
    {
        return [
             // Can also use callback validation rules
             'unit_price' => function($attribute, $value, $onFailure) {
                  if (!is_numeric($value)) {
                       $onFailure('Unit price is not numeric');
                  }
              }
        ];
    }
}
