<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SaveLongLat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SaveLongLat:Daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Saving the Longitude and Latitude of a seller in address table';

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
        $shops = \App\Shop::orderBy('id', 'DESC')->get();
        $final_add = '';
        foreach($shops as $shop){
            if(!empty($shop) && !empty($shop->address)){
                $addresses = \App\Shop::findOrFail($shop->id);
                $add = $addresses->address;
                $city = $addresses->city;
                $state = $addresses->state;
                $country = $addresses->country;
                $final_add = $add.' '.$city.' '.$state.' '.$country;
                $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$final_add.'&sensor=false&key=AIzaSyAD9bcMpr11Me8QJZgqwDTx5f5zj3WGm14';
                $url = str_replace ("#", "", $url);
                $url = str_replace (" ", "+", $url);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $geoloc = json_decode(curl_exec($ch), true);
                $step1 = $geoloc['results'];
                if(!empty($step1)){
                    $step2 = $step1[0]['geometry'];
                    $coords = $step2['location'];
                    $addresses->longitude = $coords['lng'];
                    $addresses->latitude = $coords['lat'];
                    $addresses->save();
                }
                curl_close($ch);    
            }
        }
        $this->info('---------Addresses Has Been Updated Of All The  Sellers--------');
    }
}
