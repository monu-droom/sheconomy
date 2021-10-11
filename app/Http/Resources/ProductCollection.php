<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                $seller = \App\Seller::where('user_id', $data->user_id)->first();
                return [
                    'name' => $data->name,
                    'seller_id' => $seller->id,
                    'product_id' => (integer)$data->id,
                    'photos' => json_decode($data->photos),
                    'thumbnail_image' => $data->thumbnail_img,
                    'base_price' => (double)$data->unit_price,
                    'base_price_usd' => (double)$data->price_usd,
                    'todays_deal' => (integer) $data->todays_deal,
                    'featured' =>(integer) $data->featured,
                    'unit' => $data->unit,
                    'discount' => (double) $data->discount,
                    'discount_type' => $data->discount_type,
                    'rating' => (double) $data->rating,
                    'sales' => (integer) $data->num_of_sale,
                    'share_product' => 'https://sheconomy.in/product/'.$data->slug,
                    'links' => [
                        'details' => route('products.show', $data->id),
                        'reviews' => route('api.reviews.index', $data->id),
                        'related' => route('products.related', $data->id),
                        'top_from_seller' => route('products.topFromSeller', $data->id)
                    ]
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
