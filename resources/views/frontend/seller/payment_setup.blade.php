@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 payment_setup">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @if(Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
                    @endif
                </div>
                
                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{ translate('Payment Setup')}}
                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{ translate('Dashboard')}}</a></li>
                                            <li class="active"><a href="{{ route('payment.setup') }}">{{ translate('Payment Setup')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        <form class="form-default" action="{{ route('payment.setup') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('PayPal Payment Details')}}
                                    <span class="float-right"><small><a href="https://developer.paypal.com/docs/api/overview/">Click Here </a>to get Paypal MID and Paypal KEY and Paypal Email</small></span>
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Payment Status ')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <label class="switch mb-3">
                                                <input value="1" id ='status_slider' name="payment_status" onchange="mySlider()" type="checkbox" @if ($payment->payment_status == 1) checked @endif>
                                                <span class="slider round"></span>
                                            </label>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Switch your payment status with the button given</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('PayPal MID')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('GC758HGF786HM')}}" name="mid" value="{{$payment->paypal_mid}}">
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your Paypal merchant id in this field.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('PayPal KEY')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('**********')}}" name="key" value="{{$payment->paypal_key}}">
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your Paypal key in this field.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Email linked in PayPal')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('xyz@mail.com')}}" name="email" value="{{$payment->paypal_email}}">
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the email id linked to your paypal.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Submit')}}</button>
                            </div>
                        </form>    
                        <!-- Payment setup for RaZoR-PaY -->                             
                        <form class="form-default" action="{{ route('razorpay.setup') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Razorpay Payment Details')}}
                                    <span class="float-right"><small><a href="https://developer.paypal.com/docs/api/overview/">Click Here </a>to get Razorpay KEY and Razorpay Secret</small></span>
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Payment Status ')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <label class="switch mb-3">
                                                <input value="1" id ='status_slider' name="razorpay_payment_status" onchange="mySlider()" type="checkbox" @if ($payment->razorpay_status == 1) checked @endif>
                                                <span class="slider round"></span>
                                            </label>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Switch your payment status with the button given</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Razorpay KEY')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('rzp_live_6NEMkMwtW0VXws')}}" name="razorpay_key" value="{{$payment->razorpay_key}}">
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your Paypal merchant id in this field.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Razorpay SECRET')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('**********')}}" name="razorpay_secret" value="{{$payment->razorpay_secret}}">
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your Paypal key in this field.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Submit')}}</button>
                            </div>
                        </form>    
                        <!-- Payment setup for StRiPe -->                             
                        <form class="form-default" action="{{ route('stripe.setup') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Stripe Payment Details')}}
                                    <span class="float-right"><small><a href="https://developer.paypal.com/docs/api/overview/">Click Here </a>to get Stripe KEY and Stripe Secret</small></span>
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Payment Status ')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <label class="switch mb-3">
                                                <input value="1" id ='status_slider' name="stripe_payment_status" onchange="mySlider()" type="checkbox" @if ($payment->stripe_status == 1) checked @endif>
                                                <span class="slider round"></span>
                                            </label>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Switch your payment status with the button given</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Stripe KEY')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('pk_test_TYooMQauvdEDq54NiTphI7jx')}}" name="stripe_key" value="{{$payment->stripe_key}}">
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your Paypal merchant id in this field.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Stripe SECRET')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('**********')}}" name="stripe_secret" value="{{$payment->stripe_secret}}">
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your Paypal key in this field.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Submit')}}</button>
                            </div>
                        </form> 
                        <!-- Payment setup for InstaMoJo -->                             
                        <form class="form-default" action="{{ route('instamojo.setup') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Instamojo Payment Details')}}
                                    <span class="float-right"><small><a href="https://developer.paypal.com/docs/api/overview/">Click Here </a>to get Instamojo API-KEY and Instamojo Token</small></span>
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Payment Status ')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <label class="switch mb-3">
                                                <input value="1" id ='status_slider' name="instamojo_payment_status" onchange="mySlider()" type="checkbox" @if ($payment->instamojo_status == 1) checked @endif>
                                                <span class="slider round"></span>
                                            </label>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Switch your payment status with the button given</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Instamojo API-KEY')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('pk_test_TYooMQauvdEDq54NiTphI7jx')}}" name="instamojo_key" value="{{$payment->instamojo_key}}">
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your Paypal merchant id in this field.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Instamojo TOKEN')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('**********')}}" name="instamojo_token" value="{{$payment->instamojo_token}}">
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your Paypal key in this field.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection