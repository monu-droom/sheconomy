<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateVariant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:variant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the value of Variant in product Stocks Table';

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
        $product_stocks = \App\ProductStock::get();
        foreach($product_stocks as $stocks){
            $save_stock = \App\ProductStock::findOrFail($stocks->id);
            $st = $stocks;
            $stocks = str_split($stocks->variant);
            sort($stocks);
            $stocks = str_replace('-', '', implode('', $stocks));
            $save_stock->variant = !empty($stocks) ? $stocks : '';  
            if($save_stock->save()){
                $this->info($st->variant." Changed to ".$stocks);
            }
        }     
        $this->info('Congratulations! Variant Updated Successfully!');
    }
}
