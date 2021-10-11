<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CountryCollection;
use App\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class CountryController extends Controller
{
    public function index()
    {
            return new CountryCollection(Country::get());
    } 
}
