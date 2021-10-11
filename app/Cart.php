<?php

namespace App;

use App\User;
use App\Seller;
use App\Product;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    //    Table name 
    protected $table = 'carts';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
