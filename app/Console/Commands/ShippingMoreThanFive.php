<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ShippingMoreThanFive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipping:more';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is useful for making extra json key and value pair for shipping more than 5000gm in shipping setting table';

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
        $shipping = \App\ShippingSetup::get();
        foreach($shipping as $ship){
            $ship_arr = json_decode($ship->rate_weight, true);
            $new_ar = ["more_than_5000" => 0];
            $result = array_merge($ship_arr, $new_ar);
            $save_shipping = \App\ShippingSetup::findOrFail($ship->id);
            $save_shipping->rate_weight = json_encode($result, true);
            $save_shipping->save();
        }
    }
}
