<?php

namespace App\Http\Controllers\Api;
use App\ShippingSetup;
use App\Http\Controllers\Controller;
use App\Http\Resources\SellerShippingCollection;
use Illuminate\Http\Request;

class SellerShippingController extends Controller
{
    public function index()
    {
        return new SellerShippingCollection(ShippingSetup::all());
    }
    public function show($id)
    {
        return new SellerShippingCollection(ShippingSetup::where('seller_id', $id)->get());
    } 
}
