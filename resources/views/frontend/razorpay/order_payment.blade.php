<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/custom-style.css') }}">
</head>
<body>
    <section class="py-4 mb-4 bg-light">
        <div class="container text-center">

            <form action="{!!route('api.razorpay.payment')!!}" method="POST" id='rozer-pay' style="display: none;">
                <!-- Note that the amount is in paise = 50 INR -->
                <!--amount need to be in paisa-->
                <script src="https://checkout.razorpay.com/v1/checkout.js"
                        data-key="{{ $razorpay_key }}"
                        data-amount={{round($order->grand_total) * 100}}
                        data-buttontext=""
                        data-name="{{ env('APP_NAME') }}"
                        data-description="Cart Payment"
                        data-image="https://sheconomy.in/public/uploads/logo/sheconomy-og.jpg"
                        data-prefill.name= "{{ $shipping_address['name'] }}"
                        data-prefill.email= "{{ $shipping_address['email'] }}"
                        data-theme.color="#ff7529">
                </script>
                <input type="hidden" name="razorpay_key" value="{{ $razorpay_key }}">
                <input type="hidden" name="razorpay_secret" value="{{ $razorpay_secret }}">
                <input type="hidden" name="invalid_input" id="invalid_input" value="{{ $invalid_input }}">
                <input type="hidden" name="_token" value="{!!csrf_token()!!}">
            </form>
        </div>
    </section>

    <!-- SCRIPTS -->
    <script src="{{ static_asset('assets/js/vendors.js') }}"></script>
    <script src="{{ static_asset('assets/js/aiz-core.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            var invalid = $('#invalid_input').val();
            if(invalid == 1){
                console.log('Given information is Not Valid!');
                alert('Given information is Not Valid!');
                die();
            }
            $('#rozer-pay').submit();
        });
    </script>
</body>
</html>
