@extends('frontend.layouts.app')

@section('meta_title'){{ $shop->meta_title }}@stop

@section('meta_description'){{ $shop->meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $shop->meta_title }}">
    <meta itemprop="description" content="{{ $shop->meta_description }}">
    <meta itemprop="image" content="{{ my_asset($shop->logo) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="website">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $shop->meta_title }}">
    <meta name="twitter:description" content="{{ $shop->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ my_asset($shop->meta_img) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $shop->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('shop.visit', $shop->slug) }}" />
    <meta property="og:image" content="{{ my_asset($shop->logo) }}" />
    <meta property="og:description" content="{{ $shop->meta_description }}" />
    <meta property="og:site_name" content="{{ $shop->name }}" />
@endsection

@section('content')
    <!-- <section>
        <img loading="lazy"  src="https://via.placeholder.com/2000x300.jpg" alt="" class="img-fluid">
    </section> -->
    @php
        $total = 0;
        $rating = [];
        foreach ($review as $reviews) {
            if($reviews->rating_seller != ''){
                $total += 1;
                array_push($rating, $reviews->rating_seller);
                $rating_sum = array_sum($rating);
            }
        }
    @endphp
    <section class="gry-bg pt-4 ">
        <div class="container">
            <div class="row align-items-baseline">
                <div class="col-md-6">
                    <div class="d-flex">
                        <img
                            height="70"
                            class="lazyload"
                            src="{{ static_asset('frontend/images/placeholder.jpg') }}"
                            data-src="@if ($shop->logo !== null) {{ my_asset($shop->logo) }} @else {{ static_asset('frontend/images/placeholder.jpg') }} @endif"
                            alt="{{ $shop->name }}"
                        >
                        <div class="pl-4">
                        <h3 class="strong-700 heading-4 mb-0">{{ $shop->name }}
                            @if ($shop->user->seller->verification_status == 1)
                                <span class="ml-2"><i class="fa fa-check-circle" style="color:green"></i></span>
                            @else
                                <span class="ml-2"><i class="fa fa-times-circle" style="color:red"></i></span>
                            @endif
                        </h3>
                        <div class="star-rating star-rating-sm mb-1">
                            @if ($total > 0)
                                {{ renderStarRating($rating_sum/$total) }}
                            @else
                                {{ renderStarRating(0) }}
                            @endif
                        </div>
                            <!-- @if($seller->is_hide_address == 0)
                                <div class="location alpha-6">{{ $shop->address }}</div>
                            @endif -->
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <ul class="text-md-right mt-4 mt-md-0 social-nav model-2">
                        @if ($shop->facebook != null)
                            <li>
                                <a href="{{ $shop->facebook }}" class="facebook social_a" target="_blank" data-toggle="tooltip" data-original-title="Facebook">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                        @endif
                        @if ($shop->twitter != null)
                            <li>
                                <a href="{{ $shop->twitter }}" class="twitter social_a" target="_blank" data-toggle="tooltip" data-original-title="Twitter">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                        @endif
                        @if($shop->google != null)
                        <li>
                            <a href="{{ $shop->google }}" class="twitter social_a" target="_blank" data-toggle="tooltip" data-original-title="Linkedin">
                                <i class="fa fa-linkedin"></i>
                            </a>
                        </li>
                        @endif
                        @if ($shop->youtube != null)
                            <li>
                                <a href="{{ $shop->youtube }}" class="youtube social_a" target="_blank" data-toggle="tooltip" data-original-title="Youtube">
                                    <i class="fa fa-youtube"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-white">
        <div class="container">
            <div class="row sticky-top mt-4">
                <div class="col">
                    <div class="seller-shop-menu">
                        <ul class="inline-links">
                            <li @if(!isset($type)) class="active" @endif>
                            @if($shop->domain != '')
                                <a href="{{ route('shop.visit', $shop->domain) }}">
                            @else
                                <a href="{{ route('visit.shop', $shop->name) }}">
                            @endif
                            {{ translate('Store Home')}}</a></li>
                            <!-- <li @if(isset($type) && $type == 'top_selling') class="active" @endif>
                                <a href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'top_selling']) }}">
                            {{ translate('Top Selling')}}</a></li> -->
                            @if($shop->seller_type == 'goods' || $shop->seller_type == 'both')
                            <li @if(isset($type) && $type == 'all_products') class="active" @endif>
                                <a href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'all_products']) }}">{{ translate('All Products')}}</a></li>
                            @endif
                            @if($shop->seller_type == 'services' || $shop->seller_type == 'both')
                            <li @if(isset($type) && $type == 'all_services') class="active" @endif>
                                <a href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'all_services']) }}">{{ translate('All Services')}}</a></li>
                            @endif
                            <li @if(isset($type) && $type == 'about_us') class="active" @endif>
                                <a href="{{ route('shop.visit.aboutus', ['slug'=>$shop->slug, 'id'=>$shop->id, 'type'=>'about_us']) }}">{{ translate('About Us')}}</a></li>
                            <li @if(isset($type) && $type == 'policies') class="active" @endif>
                                <a href="{{ route('shop.visit.policies', ['slug'=>$shop->slug, 'id'=>$shop->id, 'type'=>'policies']) }}">{{ translate('Policies')}}</a></li>
                            <li @if(isset($type) && $type == 'seller_review') class="active" @endif>
                                <a href="{{ route('shop.visit.seller_review', ['slug'=>$shop->slug, 'id'=>$shop->id, 'type'=>'seller_review']) }}">{{ translate('Leave Seller Feedback')}}</a></li>
                            <li @if(isset($type) && $type == 'contact_us') class="active" @endif>
                                <a href="{{ route('shop.visit.contact_us', ['slug'=>$shop->slug, 'id'=>$shop->id, 'type'=>'contact_us']) }}">{{ translate('Contact Us')}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="@if (!isset($type)) gry-bg @endif pt-5">
        <div class="container">
            <h4 class="heading-5 strong-600 border-bottom pb-3 mb-4">
                    Contact Us                
            </h4>
            <div style="background-color: #fff; padding: 10px; text-align-center; border: 3px dashed grey;" class="product-list row gutters-5 sm-no-gutters">
                <ul>
                    <li style="font-size: 16px; list-style-type: none;"><strong>Country: </strong>{{ $contact->country }}</li>
                    <li style="font-size: 16px; list-style-type: none;"><strong>Name: </strong>{{ $contact->contact_name }}</li>
                    @if($contact->is_hide_address == 0)
                        <li style="font-size: 16px; list-style-type: none;"><strong>Address: </strong>{{ $contact->address_1 }} <?php if($contact->address_2) echo ','; ?> {{ $contact->address_2 }}  <?php if($contact->address_3) echo ','; ?> {{ $contact->address_3 }} , {{ $contact->city}}, {{ $contact->zip_code }}, {{ $contact->state }}, {{ $contact->country }}</li>
                    @endif
                    <li style="font-size: 16px; list-style-type: none;"><strong>Email: </strong>{{ $contact->email }}</li>
                    @if($contact->is_hide_phone == 0)
                        <li style="font-size: 16px; list-style-type: none;"><strong>Phone: </strong>{{ $contact->phone }}</li>
                    @endif
                </ul>
            </div>
            <div class="row">
                <div class="col">
                    <div class="products-pagination my-5">
                        <nav aria-label="Center aligned pagination">
                            <ul class="pagination justify-content-center">
                                
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
