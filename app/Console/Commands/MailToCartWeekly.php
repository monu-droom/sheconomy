<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\MailToCartUserManager;
use Mail;

class MailToCartWeekly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add_to_cart:mail_weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mailing to the user who added items in the cart and not purchased that item yet';

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
        $carts = \App\Cart::where('mail_1', 1)->where('mail_3', 1)->where('mail_7', 0)->get();
        $array = [
            'view' => 'emails.cart-mail',
            'subject' => 'SHEconomy Cart Manager',
            'from' => env('MAIL_USERNAME'),
            'message' => 'We noticed you left something in your cart',
            'link' => 'https://sheconomy.in/cart'
        ];
        foreach($carts as $cart){
            $product = \App\Product::where('id', $cart->product_id)->first();
            $product_name = $product->name;
            $product_image = $product->photos;
            $array['product_name'] = $product_name;
            if(is_array(json_decode($product->photos))){
                $img = json_decode($product->photos)[0];
            }
            $array['product_image'] = $img;
            $message ="We noticed you left something in your cart. Go to link";
            $link = "https://sheconomy.in/cart";
            $users = \App\User::where('id', $cart->user_id)->first();
            $user = isset($users->email) ? $users->email : '';
            Mail::to($user)->send(new MailToCartUserManager($array));
            $message ="We noticed you left something in your cart. Go to link";
            $link = "https://sheconomy.in/cart";
            $users = \App\User::where('id', $cart->user_id)->first();
            $user = isset($users->email) ? $users->email : '';
            \Mail::raw("{$message} {$link}", function ($mail) use ($user) {
                $mail->from('donotreply@sheconomy.in');
                $mail->to($user)
                    ->subject('SHEconomy Cart Reminder');
            });
            $cart_save = \App\Cart::findOrFail($cart->id);
            $cart_save->mail_7 = 1;
            if($cart_save->save()){
                $this->info('Mail Sent to : '.$users->name);
            }
        }         
        $this->info('---------Cart Reminder Has Been Sent to Users--------');
    }
}
