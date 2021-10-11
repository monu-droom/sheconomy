<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CartCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                $user = \App\User::where('id', $data->user_id)->first();
                $user_name = $user->name;
                $seller = \App\Seller::where('user_id', $data->user_id)->first();
                $sub_total = $data->price + $data->shipping_cost;
                $sub_total = $sub_total * $data->quantity;
                return [
                    'id' => $data->id,
                    'seller_id' => $data->seller_id,
                    'seller_name' => $user_name,
                    'seller_payment' => \App\SellerPaymentSetting::where('seller_id',$seller->id)->get(),
                    'product' => [
                        'name' => $data->product->name,
                        'image' => $data->product->thumbnail_img
                    ],
                    'variation' => $data->variation,
                    'price' => (double) $data->price,
                    'tax' => (double) $data->tax,
                    'shipping_cost' => (double) $data->shipping_cost,
                    'sub_total' => number_format( (double) $sub_total, 2, '.', ''),
                    'quantity' => (integer) $data->quantity,
                    'date' => $data->created_at->diffForHumans()
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
