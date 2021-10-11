@extends('frontend.layouts.app')

@section('content')
<style>
    @media(min-width: 576px){
        .shipping-margin-left{
        margin-left: 200px;
        }
    }
    /* @media(max-width: 575px){
        .shipping-margin-left{
        margin-left: 100px;
        }
    } */
</style>
    <section class="gry-bg py-4 domain_setup">
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
                                        {{ translate('Shipping Setup')}}
                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{ translate('Dashboard')}}</a></li>
                                            <li class="active"><a href="{{ route('get.shipping.setup') }}">{{ translate('Shipping Setup')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        <form class="form-default">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Add Shipping Details')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <h6>{{ translate('Shipping Types')}}<h6>
                                        </div>
                                        <div class="offset-md-3 col-md-6">
                                            <select class="form-control" id="shipping_type" name="shipping_type">
                                              <option value="">--Select Shipping Type--</option>
                                              <option value="local">Local</option>
                                              <option value="regional">Regional</option>
                                              <option value="national">National</option>
                                              <option value="international">International</option>
                                            </select>
                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Choose your shipping types in the list given here.</p>"><i class="fa fa-question-circle"></i></button>
                                        </div>
                                    </div>
                                    <br>

                                    <div id="hide">
                                        <div class="col-md-12">
                                            <h6 class="text-center">Shipping Charges</h6>
                                            <div class="row shipping-margin-left">
                                                @if($country == 'india')
                                                    <div class="col-md-4 ml-4">
                                                    <div class="row" id="rate">
                                                    <div class="col">
                                                        <label>{{ translate('For 0-100gm')}}</label>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <span class="heading-4">₹</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control mb-3" name="rate_100" id="rate_100" value="" required>
                                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 0-100gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                                <div class="col-md-4 ml-4">
                                                <div class="row" id="rate">
                                                <div class="col">
                                                    <label>{{ translate('For 0-100gm')}}</label>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <span class="heading-4">$</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control mb-3" name="rate_100" id="rate_100" value="" required>
                                                        <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 0-100gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                                </div>
                                            @endif
                                            <!-- <br> -->
                                            @if($country == 'india')
                                                <div class="col-md-4 ml-4">
                                                <div class="row" id="rate">
                                                <div class="col">
                                                    <label>{{ translate('For 101-500gm')}}</label>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <span class="heading-4">₹</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control mb-3" name="rate_500" id="rate_500" value="" required>
                                                        <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 101-500gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                                </div>
                                            @else
                                                <div class="col-md-4 ml-4">
                                                <div class="row" id="rate">
                                                <div class="col">
                                                    <label>{{ translate('For 101-500gm')}}</label>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <span class="heading-4">$</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control mb-3" name="rate_500" id="rate_500" value="" required>
                                                        <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 101-500gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="row shipping-margin-left">
                                            @if($country == 'india')
                                            <div class="col-md-4 ml-4">
                                                <div class="row" id="rate">
                                                    <div class="col">
                                                        <label>{{ translate('For 501-1000gm')}}</label>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <span class="heading-4">₹</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control mb-3" name="rate_1000" id="rate_1000" value="" required>
                                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 501-1000gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                                @else
                                                <div class="col-md-4 ml-4">
                                                    <div class="row" id="rate">
                                                        <div class="col">
                                                            <label>{{ translate('For 501-1000gm')}}</label>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <span class="heading-4">$</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control mb-3" name="rate_1000" id="rate_1000" value="" required>
                                                                <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 501-1000gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                @if($country == 'india')
                                                <div class="col-md-4 ml-4">
                                                    <div class="row" id="rate">
                                                        <div class="col">
                                                            <label>{{ translate('For 1001-1500gm')}}</label>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <span class="heading-4">₹</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control mb-3" name="rate_1500" id="rate_1500" value="" required>
                                                                <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 1001-1500gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="col-md-4 ml-4">
                                                    <div class="row" id="rate">
                                                        <div class="col">
                                                            <label>{{ translate('For 1001-1500gm')}}</label>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <span class="heading-4">$</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control mb-3" name="rate_1500" id="rate_1500" value="" required>
                                                                <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 1001-1500gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                        </div>
                                    </div>

                                    <div  class="col-md-12">
                                    <div class="row shipping-margin-left">
                                            @if($country == 'india')
                                                <div class="col-md-4 ml-4">
                                                <div class="row" id="rate">
                                                <div class="col">
                                                    <label>{{ translate('For 1501-2000gm')}}</label>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <span class="heading-4">₹</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control mb-3" name="rate_2000" id="rate_2000" value="" required>
                                                        <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 1501-2000gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                                </div>
                                            @else
                                                <div class="col-md-4 ml-4">
                                                <div class="row" id="rate">
                                                <div class="col">
                                                    <label>{{ translate('For 1501-2000gm')}}</label>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <span class="heading-4">$</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control mb-3" name="rate_2000" id="rate_2000" value="" required>
                                                        <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 1501-2000gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                                </div>
                                            @endif
                                            <!-- <br> -->
                                            @if($country == 'india')
                                                <div class="col-md-4 ml-4">
                                                <div class="row" id="rate">
                                                <div class="col">
                                                    <label>{{ translate('For 2001-2500gm')}}</label>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <span class="heading-4">₹</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control mb-3" name="rate_2500" id="rate_2500" value="" required>
                                                        <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 2001-2500gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                                </div>
                                            @else
                                                <div class="col-md-4 ml-4">
                                                <div class="row" id="rate">
                                                <div class="col">
                                                    <label>{{ translate('For 2001-2500gm')}}</label>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <span class="heading-4">$</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control mb-3" name="rate_2500" id="rate_2500" value="" required>
                                                        <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 2001-2500gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="row shipping-margin-left">
                                            @if($country == 'india')
                                            <div class="col-md-4 ml-4">
                                                <div class="row" id="rate">
                                                    <div class="col">
                                                        <label>{{ translate('For 2501-3000gm')}}</label>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <span class="heading-4">₹</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control mb-3" name="rate_3000" id="rate_3000" value="" required>
                                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 2501-3000gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                                @else
                                                <div class="col-md-4 ml-4">
                                                    <div class="row" id="rate">
                                                        <div class="col">
                                                            <label>{{ translate('For 2501-3000gm')}}</label>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <span class="heading-4">$</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control mb-3" name="rate_3000" id="rate_3000" value="" required>
                                                                <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 2501-3000gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                @if($country == 'india')
                                                <div class="col-md-4 ml-4">
                                                    <div class="row" id="rate">
                                                        <div class="col">
                                                            <label>{{ translate('For 3001-3500gm')}}</label>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <span class="heading-4">₹</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control mb-3" name="rate_3500" id="rate_3500" value="" required>
                                                                <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 3001-3500gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="col-md-4 ml-4">
                                                    <div class="row" id="rate">
                                                        <div class="col">
                                                            <label>{{ translate('For 3001-3500gm')}}</label>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <span class="heading-4">$</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control mb-3" name="rate_3500" id="rate_3500" value="" required>
                                                                <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 3001-3500gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                        </div>
                                    </div>

                                    <div  class="col-md-12">
                                    <div class="row shipping-margin-left">
                                            @if($country == 'india')
                                                <div class="col-md-4 ml-4">
                                                <div class="row" id="rate">
                                                <div class="col">
                                                    <label>{{ translate('For 3501-4000gm')}}</label>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <span class="heading-4">₹</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control mb-3" name="rate_4000" id="rate_4000" value="" required>
                                                        <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 3501-4000gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                                </div>
                                            @else
                                                <div class="col-md-4 ml-4">
                                                <div class="row" id="rate">
                                                <div class="col">
                                                    <label>{{ translate('For 3501-4000gm')}}</label>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <span class="heading-4">$</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control mb-3" name="rate_4000" id="rate_4000" value="" required>
                                                        <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 3501-4000gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                                </div>
                                            @endif
                                            <!-- <br> -->
                                            @if($country == 'india')
                                                <div class="col-md-4 ml-4">
                                                <div class="row" id="rate">
                                                <div class="col">
                                                    <label>{{ translate('For 4001-4500gm')}}</label>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <span class="heading-4">₹</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control mb-3" name="rate_4500" id="rate_4500" value="" required>
                                                        <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 4001-4500gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                                </div>
                                            @else
                                                <div class="col-md-4 ml-4">
                                                <div class="row" id="rate">
                                                <div class="col">
                                                    <label>{{ translate('For 4001-4500gm')}}</label>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <span class="heading-4">$</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control mb-3" name="rate_4500" id="rate_4500" value="" required>
                                                        <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 4001-4500gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="row shipping-margin-left">
                                            @if($country == 'india')
                                            <div class="col-md-4 ml-4">
                                                <div class="row" id="rate">
                                                    <div class="col">
                                                        <label>{{ translate('For 4501-5000gm')}}</label>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <span class="heading-4">₹</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control mb-3" name="rate_5000" id="rate_5000" value="" required>
                                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 4501-5000gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                                @else
                                                <div class="col-md-4 ml-4">
                                                    <div class="row" id="rate">
                                                        <div class="col">
                                                            <label>{{ translate('For 4501-5000gm')}}</label>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <span class="heading-4">$</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control mb-3" name="rate_5000" id="rate_5000" value="" required>
                                                                <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for 4501-5000gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if($country == 'india')
                                            <div class="col-md-4 ml-4">
                                                <div class="row" id="rate">
                                                    <div class="col">
                                                        <label>{{ translate('For More Than 5000gm')}}</label>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <span class="heading-4">₹</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control mb-3" name="more_than_5000" id="more_than_5000" value="" required>
                                                            <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for More Than 5000gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                                @else
                                                <div class="col-md-4 ml-4">
                                                    <div class="row" id="rate">
                                                        <div class="col">
                                                            <label>{{ translate('For More Than 5000gm')}}</label>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <span class="heading-4">$</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control mb-3" name="more_than_5000" id="more_than_5000" value="" required>
                                                                <button type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter the shipping price for more than 5000gm item.</p>"><i class="fa fa-question-circle"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" onclick="shipping()" class="btn btn-styled btn-base-1">{{ translate('Submit')}}</button>
                            </div>
                        </form>    
                        <div class="panel-body">
                            <table class="table table-responsive table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="width: 120px;">{{ translate('Shipping Type')}}</th>
                                        <th>{{ translate('Shipping Price For:')}}</th>
                                        <th>{{ translate('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>                     
                                    <?php $index = 1; ?>       
                                    @foreach($shipping as $ship)
                                    <tr id="{{$index}}}">
                                        <td>{{$index}}</td>
                                        <?php $index++; ?>
                                        <td style="width: 120px;">
                                            <input type="text" class="row-data" name="shipping_region" value="{{$ship->shipping_type}}" disabled>
                                        </td>
                                        @if($country == 'india')
                                        <td>
                                            <table>
                                                <thead>
                                                    <tr class="d-flex">
                                                        <th style="width: 120px;">{{ translate('Shipping price for 0-100gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 101-500gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 501-1000gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 1001-1500gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 1501-2000gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 2001-2500gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 2501-3000gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 3001-3500gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 3501-4000gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 4001-4500gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 4501-5000gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for more than 5000gm')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                
                                                <?php
                                                    $rates = json_decode($ship->rate_weight, true);
                                                ?>       
                                                <tr class="d-flex">
                                                @foreach($rates as $key => $rate)
                                                    <td style="width: 120px;">
                                                        <!-- <span style="font-size: 18px">₹</span> -->
                                                        <?php $rate = (double)$rate * 73?> 
                                                        ₹<input type="text" style="width: 40px" class="row-data" name="{{$key}}" value='{{ceil($rate)}}'>
                                                    </td>
                                                @endforeach
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        @else
                                        <td>
                                            <table>
                                                <thead>
                                                    <tr class="d-flex">
                                                        <th style="width: 120px;">{{ translate('Shipping price for 0-100gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 101-500gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 501-1000gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 1001-1500gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 1501-2000gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 2001-2500gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 2501-3000gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 3001-3500gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 3501-4000gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 4001-4500gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for 4501-5000gm')}}</th>
                                                        <th style="width: 120px;">{{ translate('Shipping price for more than 5000gm')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                <?php
                                                    $rates = json_decode($ship->rate_weight, true);
                                                ?>       
                                                <tr class="d-flex">

                                                @foreach($rates as $key => $rate)
                                                    <td style="width: 120px;">
                                                        $<input type="text" style="width: 40px" class="row-data" name="{{$key}}" value="{{substr(single_price($rate),1)}}">
                                                    </td>
                                                @endforeach
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        @endif
                                        <td>
                                            <button type="submit" onclick="update()" class="btn btn-styled btn-base-1">{{ translate('Update')}}</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">
    $('#hide').hide();
    $('#shipping_type').change(function(){
            $('#hide').show(); 
    });
        function update() {            
            var rowId = event.target.parentNode.parentNode.id;
            var data = document.getElementById(rowId).querySelectorAll(".row-data");
            var shipping = data[0].value;
            var rate_array = {};
            rate_array[data[1].name] = data[1].value;
            rate_array[data[2].name] = data[2].value; 
            rate_array[data[3].name] = data[3].value; 
            rate_array[data[4].name] = data[4].value; 
            rate_array[data[5].name] = data[5].value; 
            rate_array[data[6].name] = data[6].value; 
            rate_array[data[7].name] = data[7].value; 
            rate_array[data[8].name] = data[8].value; 
            rate_array[data[9].name] = data[9].value; 
            rate_array[data[10].name] = data[10].value;
            rate_array[data[11].name] = data[11].value;
            rate_array[data[12].name] = data[12].value;
            $.post('{{ route('shipping.update') }}', {_token:'{{ csrf_token() }}',
                shipping:shipping,
                rate_array:rate_array,
                },
                function(data){
                data = JSON.parse(data);
                if(data.status == 1){
                    showFrontendAlert('success', data.message);
                location.reload();
                }else
                    showFrontendAlert('danger', data.message);
                location.reload();
            });
        }
        function shipping() {            
            var tab = $("#shipping_type").val();
            var rate_100 = $("#rate_100").val();
            var rate_500 = $("#rate_500").val();
            var rate_1000 = $("#rate_1000").val();
            var rate_1500 = $("#rate_1500").val();
            var rate_2000 = $("#rate_2000").val();
            var rate_2500 = $("#rate_2500").val();
            var rate_3000 = $("#rate_3000").val();
            var rate_3500 = $("#rate_3500").val();
            var rate_4000 = $("#rate_4000").val();
            var rate_4500 = $("#rate_4500").val();
            var rate_5000 = $("#rate_5000").val();
            var more_than_5000 = $("#more_than_5000").val();
            $.post('{{ route('shipping.setup') }}', {_token:'{{ csrf_token() }}',
                tab:tab,
                rate_100:rate_100,
                rate_500:rate_500,
                rate_1000:rate_1000,
                rate_1500:rate_1500,
                rate_2000:rate_2000,
                rate_2500:rate_2500,
                rate_3000:rate_3000,
                rate_3500:rate_3500,
                rate_4000:rate_4000,
                rate_4500:rate_4500,
                rate_5000:rate_5000,
                more_than_5000:more_than_5000,
                },
                function(data){
                data = JSON.parse(data);
                if(data.status == 1){
                    showFrontendAlert('success', data.message);
                location.reload();
                }else
                    showFrontendAlert('danger', data.message);
                location.reload();
            });
        }        
    </script>
@endsection