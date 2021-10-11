<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerCancelOrderMailManager extends Mailable
{
    use Queueable, SerializesModels;

    public $array;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($array)
    {
        $this->array=$array;
    }
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
     {
         return $this->view($this->array['seller_view'])
                     ->from($this->array['from'], env('MAIL_FROM_NAME'))
                     ->subject($this->array['seller_subject']);
     }
}
