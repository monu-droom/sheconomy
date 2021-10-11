@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @include('frontend.inc.seller_side_nav')
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{ translate('Policy Settings')}}
                                        <a href="{{ route('shop.visit', $shop->domain) }}" class="btn btn-link btn-sm" target="_blank">({{ translate('Visit Shop')}})<i class="la la-external-link"></i>)</a>
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{ translate('Dashboard')}}</a></li>
                                            <li><a class="active" href="">{{ translate('Policy')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form class="" action="{{ route('seller.policies', $shop->id ) }}" method="POST">
                        
                        @csrf
                        <div class="form-box bg-white mt-4">
                            <div class="form-box-title px-3 py-2">
                                {{ translate('Policy Settings')}}
                            </div>
                            <div class="form-box-content p-3">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>{{ translate('Refund Policy')}} <span class="required-star">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea name="refund_policy" rows="10" placeholder="Add your refund policy here" class="form-control editor mb-3" required>{{ $shop->refund_policy }}</textarea>
                                        <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your refund policy</p>"><i class="fa fa-question-circle"></i></a>
                                    </div>
                                </div><br>

                                <?php $shop = Auth::user()->shop; ?>
                                @if($shop->seller_type == 'goods' || $shop->seller_type == 'both')
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>{{ translate('Shipping Policy')}} <span class="required-star">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea name="shipping_policy" rows="10" placeholder="Add your shipping policy here" class="form-control editor mb-3" required>{{ $shop->shipping_policy }}</textarea>
                                        <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your shipping policy</p>"><i class="fa fa-question-circle"></i></a>
                                    </div>
                                </div><br>
                                @endif

                                <div class="row">
                                    <div class="col-md-2">
                                        <label>{{ translate('Payment Policy')}} <span class="required-star">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea name="payment_policy" rows="10" placeholder="Add your payment policy here" class="form-control editor mb-3" required>{{ $shop->payment_policy }}</textarea>
                                        <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add your payment policy</p>"><i class="fa fa-question-circle"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right mt-4">
                                <button type="submit" class="btn btn-styled btn-base-1">{{ translate('Save')}}</button>
                            </div>
                    </form>
                    </div>
                </div>
            </div>
        </section>
    @endsection