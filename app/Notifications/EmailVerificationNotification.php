<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use App\Mail\EmailManager;
use Auth;
use Session;
use App\User;

class EmailVerificationNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $code = encrypt($notifiable->id);
        $notifiable->verification_code = $code;        
        if($notifiable->save()){
            $array['view'] = 'emails.verification';
            $array['subject'] = translate('Verify Your Email');
            if(Session::has('otp')){
                $otp = Session::get('otp');
                $array['content'] = 'Welcome to SHEconomy. To activate your SHEconomy account you must first verify your email address by entering the otp below';
                $array['link'] = $otp;
            }else{
                $array['content'] = 'Welcome to SHEconomy. To activate your SHEconomy account you must first verify your email address by clicking button below';
                $array['link'] = route('email.verification.confirmation', $notifiable->verification_code);            
            }
        }
        return (new MailMessage)
            ->view('emails.verification', ['array' => $array])
            ->subject(translate('Email Verification - ').env('APP_NAME'));
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
