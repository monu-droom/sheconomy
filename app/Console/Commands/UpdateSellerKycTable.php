<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\MailToCartUserManager;
use Mail;

class UpdateSellerKycTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seller:kyc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new entry in seller kyc table';

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
        $sellers = \App\Seller::get();
        foreach($sellers as $seller){
            $is_seller_kyc = \App\SellerKyc::where('seller_id', $seller->id)->first();
            if(!$is_seller_kyc){
                $new_seller_kyc = new \App\SellerKyc;
                $new_seller_kyc->seller_id = !empty($seller->id) ? $seller->id : '';
                if($new_seller_kyc->save()){
                    $this->info('New entry for Seller '.$seller->id." has been added successfully!");
                }
            }
        }
        $this->info('Laravel has completed its work Successfully!');
    }
}
