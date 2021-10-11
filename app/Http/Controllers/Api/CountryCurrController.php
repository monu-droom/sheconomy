<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CountryCurrCollection;
use App\Models\Currency;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class CountryCurrController extends Controller
{
    public function index($country)
    {
        if(strtolower($country) == 'india'){
            return new CountryCurrCollection(Currency::where('code', 'Rupee')->get());
        }else{
            return new CountryCurrCollection(Currency::where('code', 'USD')->get());            
        }
    } 
}
