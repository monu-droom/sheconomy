<?php

namespace App\Http\Controllers\Api;
use App\SellerPaymentSetting;
use App\Http\Resources\SellerPaymentCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerPaymentController extends Controller
{
    public function index()
    {
        return new SellerPaymentCollection(SellerPaymentSetting::all());
    }
    public function show($id)
    {
        return new SellerPaymentCollection(SellerPaymentSetting::where('seller_id', $id)->get());
    }    
}
