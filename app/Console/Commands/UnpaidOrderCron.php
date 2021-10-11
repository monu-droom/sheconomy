<?php

namespace App\Console\Commands;
use App\Mail\UnpaidOrderCronManager;
use Mail;  

use Illuminate\Console\Command;

class UnpaidOrderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unpaid:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unpaid orders every 3 days';

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
        $orders = \App\Order::where('payment_status', 'unpaid')->get();
        $array = [
            'view' => 'emails.payment-failed',
            'subject' => 'SHEconomy: Payment Failed',
            'from' => env('MAIL_USERNAME'),
        ];
        foreach($orders as $order){
            if(!empty($order)){
                if($order->cron_status == 0){
                    $order_save = \App\Order::findOrFail($order->id);
                    $order_save->cron_status = 1;
                    $order_save->save();
                    $message ="Your payment has been failed. The detail will only be available till 3 days.";
                    $link = "https://sheconomy.in/orders";
                    $users = \App\User::where('id', $order->user_id)->first();
                    $user = isset($users->email) ? $users->email : '';
                    Mail::to($user)->send(new UnpaidOrderCronManager($array));
                }
                elseif($order->cron_status == 1){
                    $order_save = \App\Order::findOrFail($order->id);
                    $order_save->cron_status = 2;
                    $order_save->save();
                }
                elseif($order->cron_status == 2){
                    $order_save = \App\Order::findOrFail($order->id);
                    $order_save->cron_status = 3;
                    $order_save->save();
                }
                elseif($order->cron_status == 3){
                    $order_delete = \App\Order::findOrFail($order->id);
                    $order_details = \App\OrderDetail::where('order_id', $order_delete->id)->first();
                    $order_id  = $order_delete->id;
                    if($order_delete->delete()){
                        if($order_details){
                            $order_details->delete();                            
                        }
                        $this->info('Order id : '.$order_id.' has been deleted');
                    }
                }
            }
        }
        $this->info('---------Order with Unpaid status will be deleted After Three days--------');

    }
}
