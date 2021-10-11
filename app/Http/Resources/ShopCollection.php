<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShopCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'name' => $data->name,
                    'user' => [
                        'name' => $data->user->name,
                        'email' => $data->user->email,
                        'phone' => $data->user->phone,
                        'avatar' => $data->user->avatar,
                        'avatar_original' => $data->user->avatar_original
                    ],
                    'logo' => $data->logo,
                    'seller_id' => $data->user_id,
                    'domain' => $data->domain.".sheconomy.in",
                    'sliders' => json_decode($data->sliders),
                    'address' => $data->address,
                    'facebook' => $data->facebook,
                    'google' => $data->google,
                    'twitter' => $data->twitter,
                    'youtube' => $data->youtube,
                    'instagram' => $data->instagram,
                    'about' => $data->about,
                    'address' => $data->address,
                    'state' => $data->state,
                    'city' => $data->city,
                    'seller_type' => $data->seller_type,
                    'home_text' => $data->home_text,
                    'shipping_cost' => $data->shipping_cost,
                    'refund_policy' => $data->refund_policy,
                    'shipping_policy' => $data->shipping_policy,
                    'payment_policy' => $data->payment_policy,
                    'links' => [
                        'featured' => route('shops.featuredProducts', $data->id),
                        'top' => route('shops.topSellingProducts',  $data->id),
                        'new' => route('shops.newProducts', $data->id),
                        'all' => route('shops.allProducts', $data->id),
                        'brands' => route('shops.brands', $data->id)
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
