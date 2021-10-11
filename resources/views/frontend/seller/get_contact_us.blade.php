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
                                        {{ translate('Shop Settings')}}
                                        <a href="{{ route('shop.visit', $shop->domain) }}" class="btn btn-link btn-sm" target="_blank">({{ translate('Visit Shop')}})<i class="la la-external-link"></i>)</a>
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{ translate('Dashboard')}}</a></li>
                                            <li class="active"><a href="{{ route('shops.index') }}">{{ translate('Shop Settings')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-box-content bg-white">
                            <div class="form-box-title px-3 py-2">
                                {{ translate('Contact Us') }} <span><small>( Click on hide button if you don't want them to be public )</small></span>
                            </div>
                               
                               <form action="{{ route('shop.post_contact')}}" method="POST">
                                   @csrf
                               <div class="row gutters-10">
                                        <div class="col-lg-12">
                                            <div class="border p-3 pr-5 rounded position-relative">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>{{ translate('Contact Person')}} <span class="required-star">*</span></label>
                                                        <input type="text" class="form-control mb-3" name="contact_name" placeholder="{{ translate('Enter your name')}}" value="" required>
                                                        <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your name here to get published to your customers</p>"><i class="fa fa-question-circle"></i></a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>{{ translate('Company Name')}} <span class="required-star">*</span></label>
                                                        <input type="text" class="form-control mb-3" name="company_name" placeholder="{{ translate('Enter your company name')}}" value="" required>
                                                        <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your company name here to get published to your customrers</p>"><i class="fa fa-question-circle"></i></a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>{{ translate('Address Line 1')}} <span class="required-star">*</span></label>
                                                        <input type="text" class="form-control mb-3" name="address_1" placeholder="{{ translate('Address Line 1')}}" value="" required>
                                                        <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add address here. You can add your building name, care taker name, etc. </p>"><i class="fa fa-question-circle"></i></a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>{{ translate('Address Line 2')}} </label>
                                                        <input type="text" class="form-control mb-3" name="address_2" placeholder="{{ translate('Address Line 2')}}" value="">
                                                        <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add address here. You can add your location or place in this field</p>"><i class="fa fa-question-circle"></i></a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>{{ translate('Address Line 3')}} </label>
                                                        <input type="text" class="form-control mb-3" name="address_3" placeholder="{{ translate('Address Line 3')}}" value="">
                                                        <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Add address here. You can add nearby places from your location in this field.</p>"><i class="fa fa-question-circle"></i></a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>{{ translate('State')}} <span class="required-star">*</span></label>
                                                        <input type="text" class="form-control mb-3" name="state" placeholder="{{ translate('Enter state name here')}}" value="" required>
                                                        <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your state name which will be used in your address to your customers</p>"><i class="fa fa-question-circle"></i></a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>{{ translate('City')}} <span class="required-star">*</span></label>
                                                        <input type="text" class="form-control mb-3" name="city" placeholder="{{ translate('Enter your city here')}}" value="" required>
                                                        <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your city name here which will be used in your address to your customers</p>"><i class="fa fa-question-circle"></i></a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>{{ translate('PIN / ZIP Code')}} <span class="required-star">*</span></label>
                                                        <input type="text" class="form-control mb-3" name="zip_code" placeholder="{{ translate('Enter your ZIP code here')}}" value="" required>
                                                        <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your city pin or zip code here which will be used in your address to your customers </p>"><i class="fa fa-question-circle"></i></a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="control-label">{{ translate('Select your country')}}<span class="required-star">*</span></label>
                                                            <select class="form-control custome-control" data-live-search="true" name="country">
                                                                @foreach (\App\Country::where('status', 1)->get() as $key => $country)
                                                                    <option value="{{ $country->name }}">{{ $country->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>{{ translate('Email')}} <span class="required-star">*</span></label>
                                                        <input type="text" class="form-control mb-3" name="email" placeholder="{{ translate('Enter your email id here')}}" value="" required>
                                                        <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your email id which will be used as a contact by your customers.</p>"><i class="fa fa-question-circle"></i></a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>{{ translate('Phone')}} <span class="required-star">*</span></label>
                                                        <input type="text" class="form-control mb-3" name="phone" placeholder="{{ translate('Enter your phone number here')}}" value="" required>
                                                        <a type="button" class="text-danger ibutton" data-toggle="popover" data-content="<p>Enter your phone number which will be used as a contact by your customers </p>"><i class="fa fa-question-circle"></i></a>
                                                    </div>
                                                </div>
                                                <div class="text-right m-3">
                                                    <button type="submit" class="btn btn-styled btn-base-1">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                               </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $("#hide_phone").change(function() {
            var selected = '';
            if($(this).prop('checked')) {
                selected = 1;
            } else {
                selected = 0;
            }
            $.post('{{ route('hide.seller.phone', $shop->user_id) }}', {_token:'{{ csrf_token() }}',
                selected: selected
            },
                function(data){
                data = JSON.parse(data);
                if(data.status == 1)
                    showFrontendAlert('success', data.message);
                else
                    showFrontendAlert('danger', data.message);
                location.reload();    
            });    
        });
    });
</script>

<script>
    $(document).ready(function(){
        $("#hide_address").change(function() {
            var selected = '';
            if($(this).prop('checked')) {
                selected = 1;
            } else {
                selected = 0;
            }
            $.post('{{ route('hide.seller.address', $shop->user_id) }}', {_token:'{{ csrf_token() }}',
                selected: selected
            },
                function(data){
                data = JSON.parse(data);
                if(data.status == 1)
                    showFrontendAlert('success', data.message);
                else
                    showFrontendAlert('danger', data.message);
                location.reload();    
            });    
        });
    });
</script>
@endsection

