<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PurchaseHistoryCollection;
use App\Models\Order;
use App\Models\OrderDetail;

class PurchaseHistoryController extends Controller
{
    public function index($id)
    {
        return new PurchaseHistoryCollection(Order::where('user_id', $id)->latest()->get());
    }
}
