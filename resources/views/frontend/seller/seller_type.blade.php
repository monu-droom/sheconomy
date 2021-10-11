@extends('frontend.layouts.app')

@section('content')

<?php 
    // dd($shop);
?>
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
                                        {{ translate('Update seller type')}}
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{ translate('Seller type')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form class="" action="{{ route('update_seller_type')}}" method="POST">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Seller Type')}}
                                </div>
                            <div class="form-box bg-white mt-4 m-4">
                                <select name="seller_type" required id="" class="form-control">
                                    <option value="">--Select Seller Type--</option>
                                    <option value="goods" <?php if($shop->seller_type == 'goods'){ ?> selected <?php } ?>>Want to sell goods</option>
                                    <option value="services" <?php if($shop->seller_type == 'services'){ ?> selected <?php } ?>>Want to sell services</option>
                                    <option value="both" <?php if($shop->seller_type == 'both'){ ?> selected <?php } ?>>Want to sell both</option>
                                </select>
                            </div>
                            
                            
                            <div class="form-box mt-4 text-center">
                                <button type="submit" class="btn btn-styled btn-base-1 m-3">{{  translate('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

