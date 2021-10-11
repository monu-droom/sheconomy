<?php

namespace App\Console\Commands;
use App\Product;
use App\ProductStock;
use Illuminate\Console\Command;

class UpdatePrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command will update the price in database in Products and Product_stocks table.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $products = Product::get();
        $product_stocks = ProductStock::get();
        foreach($products as $product){
            if($product->price_usd == 0){
                $price_usd = $product->unit_price;
                $price_inr = round($price_usd * 73);
                $new_product = Product::findOrFail($product->id);
                $new_product->unit_price = $price_inr;
                $new_product->price_usd = $price_usd;
                if($new_product->save()){
                    foreach($product_stocks as $product_stock){
                        if($product_stock->price_usd == 0){
                            $stock_price_usd = $product_stock->price;
                            $stock_price_inr = round($stock_price_usd * 73);
                            $new_product_stock = ProductStock::findOrFail($product_stock->id);
                            $new_product_stock->price = $stock_price_inr;
                            $new_product_stock->price_usd = $stock_price_usd;
                            $new_product_stock->save();
                        }
                    }
                }
            }
            $this->info('Price Update for Product '.$product->id." has been successfully done!");  
        }
    }
}
