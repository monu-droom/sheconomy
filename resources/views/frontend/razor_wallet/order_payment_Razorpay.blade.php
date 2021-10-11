@extends('frontend.layouts.app')

@section('content')

    <form action="{!!route('payment.rozer')!!}" method="POST" id='rozer-pay' style="display: none;">
        <!-- Note that the amount is in paise = 50 INR -->
        <!--amount need to be in paisa-->
        <input type="hidden" name="razorpay_key" value="{{ $seller_payment_setting->razorpay_key }}">
        <input type="hidden" name="razorpay_secret" value="{{ $seller_payment_setting->razorpay_secret }}">
        <script src="https://checkout.razorpay.com/v1/checkout.js"
                data-key="{{ $seller_payment_setting->razorpay_key }}"
                data-amount={{str_replace('Rs', '', single_price($order->grand_total)) * 100}}
                data-buttontext=""
                data-name="{{ $user->name }}"
                data-description="Cart Payment"
                data-image="{{ my_asset(\App\GeneralSetting::first()->logo) }}"
                data-prefill.name= "{{ $buyer->name }}"
                data-prefill.email= "{{ $buyer->email }}"
                data-theme.color="#ff7529">
        </script>
        <input type="hidden" name="_token" value="{!!csrf_token()!!}">
    </form>

@endsection

@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#rozer-pay').submit()
        });
    </script>
@endsection
