<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeliveryStatusMailManager extends Mailable
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
         return $this->view($this->array['view'])
                     ->from($this->array['from'], env('MAIL_FROM_NAME'))
                     ->subject($this->array['subject']);
     }
}