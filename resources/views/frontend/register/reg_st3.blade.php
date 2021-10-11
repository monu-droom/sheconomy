@extends('frontend.layouts.app')

@section('content')

<section class="gry-bg py-4 profile">
    <div class="container">
        <div class="row cols-xs-space cols-sm-space cols-md-space">
            <div class="col-lg-12">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <!-- <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                                            <li><a href="">{{ translate('Dashboard')}}</a></li>
                                            <li><a href="">{{ translate('Step 1')}}</a></li>
                                            <li><a href="">{{ translate('Step 2')}}</a></li>
                                            <li class="active"><a href="">{{ translate('Step 3')}}</a></li>
                                        </ul> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                          <div>
                            <div class="arrow-steps clearfix">
                                <div class="step"> <span> Step 1</span> </div>
                                <div class="step"> <span>Step 2</span> </div>
                                <div class="step current"> <span> Step 3</span> </div>
                                <div class="step"> <span>Step 4</span> </div>
                                <div class="step"> <span>Step 5</span> </div>
                                <div class="step"> <span>Step 6</span> </div>
                            </div>
                          </div>
                          <form class="form-default" action="{{ route('steps.paypal') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Payment Details')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Payment Status ')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <label class="switch mb-3">
                                                @if($payment->payment_status == 1)
                                                    <input value="1" id ='status_slider' name="payment_status" onchange="mySlider()" type="checkbox" checked>    
                                                @else
                                                    <input value="1" id ='status_slider' name="payment_status" onchange="mySlider()" type="checkbox">
                                                @endif
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('PayPal MID')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('GC758HGF786HM')}}" name="mid" value="{{ $payment->paypal_mid }}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('PayPal KEY')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('**********')}}" name="key" value="{{ $payment->paypal_key  }}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Email linked in PayPal')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" placeholder="{{ translate('xyz@mail.com')}}" name="email" value="{{ $payment->paypal_email }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Submit')}}</button>
                                </div><br>
                            </div>
                        </form>

                        <!-- Payment setup for RaZoR-PaY -->                             
                        <form class="form-default" action="{{ route('steps.razorpay') }}" method="POST" enctype="multipart/form-data">
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
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('rzp_live_6NEMkMwtW0VXws')}}" name="razorpay_key" value="{{$payment->razorpay_key}}" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your Paypal merchant id in this field.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Razorpay SECRET')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('**********')}}" name="razorpay_secret" value="{{$payment->razorpay_secret}}" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your Paypal key in this field.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Submit')}}</button>
                                </div><br>
                            </div>
                        </form>    
                        <!-- Payment setup for StRiPe -->                             
                        <form class="form-default" action="{{ route('steps.stripe') }}" method="POST" enctype="multipart/form-data">
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
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('pk_test_TYooMQauvdEDq54NiTphI7jx')}}" name="stripe_key" value="{{$payment->stripe_key}}" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your Paypal merchant id in this field.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Stripe SECRET')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('**********')}}" name="stripe_secret" value="{{$payment->stripe_secret}}" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your Paypal key in this field.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Submit')}}</button>
                                </div><br>
                            </div>
                        </form> 
                        <!-- Payment setup for InstaMoJo -->                             
                        <form class="form-default" action="{{ route('steps.instamojo') }}" method="POST" enctype="multipart/form-data">
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
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('pk_test_TYooMQauvdEDq54NiTphI7jx')}}" name="instamojo_key" value="{{$payment->instamojo_key}}" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your Paypal merchant id in this field.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Instamojo TOKEN')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control mb-3" placeholder="{{ translate('**********')}}" name="instamojo_token" value="{{$payment->instamojo_token}}" required>
                                            <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your Paypal key in this field.</p>"><i class="fa fa-question-circle"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Submit')}}</button>
                                </div><br>
                            </div>
                            <div class="text-center mt-4">
                                <?php
                                    $seller = \App\Seller::where('user_id', Auth::user()->id)->first();
                                    $payment_status = \App\SellerPaymentSetting::where('seller_id', $seller->id)->first(); 
                                ?>
                                @if($payment_status != '')
                                <button type="button" onclick="location.href='{{ route('steps.shipping') }}'" class="btn btn-styled btn-base-1">{{ translate('Skip')}}</button>
                                @endif
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection