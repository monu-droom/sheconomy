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

    @if (!isset($type))
        <section class="py-4">
            <div class="container">
                <div class="home-slide">
                    <div class="slick-carousel" data-slick-arrows="true" data-slick-dots="true">
                        @if ($shop->sliders != null)
                            @foreach (json_decode($shop->sliders) as $key => $slide)
                                <div class="">
                                    <img class="d-block w-100 lazyload" src="{{ static_asset('frontend/images/placeholder-rect.jpg') }}" data-src="{{ my_asset($slide) }}" alt="{{ $key }} slide" style="max-height:400px;">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <section class="py-4">
            <div class="container">
                <p class="heading-5 text-center"><?php echo $shop->home_text ?></p>
            </div>
        </section>
        <section class="sct-color-1 pt-5 pb-4">
            <div class="container">
                <div class="section-title section-title--style-1 text-center mb-4">
                    <h3 class="section-title-inner heading-3 strong-600">
                        {{ translate('Top Sellings')}}
                    </h3>
                </div>
                
                <div class="row">
                    <div class="col">
                        <div class="caorusel-box arrow-round gutters-15">
                            <div class="slick-carousel center-mode" data-slick-items="5" data-slick-lg-items="3"  data-slick-md-items="3" data-slick-sm-items="1" data-slick-xs-items="1" data-slick-autoplay='true'>
                                @foreach ($shop->user->products->where('published', 1)->where('num_of_sale', 1) as $key => $product)
                                    <div class="caorusel-card my-5">
                                        <div class="product-card-2 card card-product shop-cards shop-tech">
                                            <div class="card-body p-0">

                                                <div class="card-image">
                                                    <a href="{{ route('product', $product->slug) }}" class="d-block">
                                                        <img  class="mx-auto img-fit lazyload" src="{{ static_asset('frontend/images/placeholder.jpg') }}" data-src="{{ my_asset($product->thumbnail_img) }}" alt="{{  __($product->name) }}">
                                                    </a>
                                                </div>

                                                <div class="p-3">
                                                    <div class="price-box">
                                                        @if(home_price($product->id) != home_discounted_price($product->id))
                                                        <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                                        <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                                        @else
                                                        <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="star-rating star-rating-sm mt-1">
                                                        {{ renderStarRating($product->rating) }}
                                                    </div>
                                                    <h2 class="product-title p-0 text-truncate-2">
                                                        <a href="{{ route('product', $product->slug) }}">{{  __($product->name) }}</a>
                                                    </h2>
                                                    @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                                                        <div class="club-point mt-2 bg-soft-base-1 border-light-base-1 border">
                                                            {{  translate('Club Point') }}:
                                                            <span class="strong-700 float-right">{{ $product->earn_point }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        @if($shop->seller_type == 'goods' || $shop->seller_type == 'both')
        <section class="sct-color-1 pt-5 pb-4">
            <div class="container">
                <div class="section-title section-title--style-1 text-center mb-4">
                    <h3 class="section-title-inner heading-3 strong-600">
                        {{ translate('Featured Products')}}
                    </h3>
                </div>
                
                <div class="row">
                    <div class="col">
                        <div class="caorusel-box arrow-round gutters-15">
                            <div class="slick-carousel center-mode" data-slick-items="5" data-slick-lg-items="3"  data-slick-md-items="3" data-slick-sm-items="1" data-slick-xs-items="1" data-slick-autoplay='true'>
                                @foreach ($shop->user->products->where('published', 1)->where('featured', 1)->where('digital', 0) as $key => $product)
                                    <div class="caorusel-card my-5">
                                        <div class="product-card-2 card card-product shop-cards shop-tech">
                                            <div class="card-body p-0">

                                                <div class="card-image">
                                                    <a href="{{ route('product', $product->slug) }}" class="d-block">
                                                        <img  class="mx-auto img-fit lazyload" src="{{ static_asset('frontend/images/placeholder.jpg') }}" data-src="{{ my_asset($product->thumbnail_img) }}" alt="{{  __($product->name) }}">
                                                    </a>
                                                </div>

                                                <div class="p-3">
                                                    <div class="price-box">
                                                        @if(home_price($product->id) != home_discounted_price($product->id))
                                                        <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                                        <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                                        @else
                                                        <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="star-rating star-rating-sm mt-1">
                                                        {{ renderStarRating($product->rating) }}
                                                    </div>
                                                    <h2 class="product-title p-0 text-truncate-2">
                                                        <a href="{{ route('product', $product->slug) }}">{{  __($product->name) }}</a>
                                                    </h2>
                                                    @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                                                        <div class="club-point mt-2 bg-soft-base-1 border-light-base-1 border">
                                                            {{  translate('Club Point') }}:
                                                            <span class="strong-700 float-right">{{ $product->earn_point }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
        @if($shop->seller_type == 'services')
        <section class="sct-color-1 pt-5 pb-4">
            <div class="container">
                <div class="section-title section-title--style-1 text-center mb-4">
                    <h3 class="section-title-inner heading-3 strong-600">
                        {{ translate('Featured Services')}}
                    </h3>
                </div>
                
                <div class="row">
                    <div class="col">
                        <div class="caorusel-box arrow-round gutters-15">
                            <div class="slick-carousel center-mode" data-slick-items="5" data-slick-lg-items="3"  data-slick-md-items="3" data-slick-sm-items="1" data-slick-xs-items="1" data-slick-autoplay='true'>
                                @foreach ($shop->user->products->where('published', 1)->where('featured', 1)->where('digital', 1) as $key => $product)
                                    <div class="caorusel-card my-5">
                                        <div class="product-card-2 card card-product shop-cards shop-tech">
                                            <div class="card-body p-0">

                                                <div class="card-image">
                                                    <a href="{{ route('product', $product->slug) }}" class="d-block">
                                                        <img  class="mx-auto img-fit lazyload" src="{{ static_asset('frontend/images/placeholder.jpg') }}" data-src="{{ my_asset($product->thumbnail_img) }}" alt="{{  __($product->name) }}">
                                                    </a>
                                                </div>

                                                <div class="p-3">
                                                    <div class="price-box">
                                                        @if(home_price($product->id) != home_discounted_price($product->id))
                                                        <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                                        <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                                        @else
                                                        <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="star-rating star-rating-sm mt-1">
                                                        {{ renderStarRating($product->rating) }}
                                                    </div>
                                                    <h2 class="product-title p-0 text-truncate-2">
                                                        <a href="{{ route('product', $product->slug) }}">{{  __($product->name) }}</a>
                                                    </h2>
                                                    @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                                                        <div class="club-point mt-2 bg-soft-base-1 border-light-base-1 border">
                                                            {{  translate('Club Point') }}:
                                                            <span class="strong-700 float-right">{{ $product->earn_point }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
        @if($shop->seller_type == 'services')
        <section class="sct-color-1 pt-5 pb-4">
            <div class="container">
                <div class="section-title section-title--style-1 text-center mb-4">
                    <h3 class="section-title-inner heading-3 strong-600">
                        {{ translate('New Arrived Services')}}
                    </h3>
                </div>
                
                <div class="row">
                    <div class="col">
                        <div class="caorusel-box arrow-round gutters-15">
                            <div class="slick-carousel center-mode" data-slick-items="5" data-slick-lg-items="3"  data-slick-md-items="3" data-slick-sm-items="1" data-slick-xs-items="1" data-slick-autoplay='true'>
                                @foreach ($shop->user->products->where('published', 1)->where('featured', 1)->where('digital', 1) as $key => $product)
                                <?php 
                                    $date = (new \Carbon\Carbon)->parse($product->updated_at);
                                    $now = \Carbon\Carbon::now();
                                    $diff = $date->diffInDays($now);
                                ?>
                                @if($diff < 31)
                                    <div class="caorusel-card my-5">
                                        <div class="product-card-2 card card-product shop-cards shop-tech">
                                            <div class="card-body p-0">

                                                <div class="card-image">
                                                    <a href="{{ route('product', $product->slug) }}" class="d-block">
                                                        <img  class="mx-auto img-fit lazyload" src="{{ static_asset('frontend/images/placeholder.jpg') }}" data-src="{{ my_asset($product->thumbnail_img) }}" alt="{{  __($product->name) }}">
                                                    </a>
                                                </div>

                                                <div class="p-3">
                                                    <div class="price-box">
                                                        @if(home_price($product->id) != home_discounted_price($product->id))
                                                        <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                                        <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                                        @else
                                                        <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="star-rating star-rating-sm mt-1">
                                                        {{ renderStarRating($product->rating) }}
                                                    </div>
                                                    <h2 class="product-title p-0 text-truncate-2">
                                                        <a href="{{ route('product', $product->slug) }}">{{  __($product->name) }}</a>
                                                    </h2>
                                                    @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                                                        <div class="club-point mt-2 bg-soft-base-1 border-light-base-1 border">
                                                            {{  translate('Club Point') }}:
                                                            <span class="strong-700 float-right">{{ $product->earn_point }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
        @if($shop->seller_type == 'goods' || $shop->seller_type == 'both')
        <section class="sct-color-1 pt-5 pb-4">
            <div class="container">
                <div class="section-title section-title--style-1 text-center mb-4">
                    <h3 class="section-title-inner heading-3 strong-600">
                        {{ translate('New Arrived Products')}}
                    </h3>
                </div>                
                <div class="row">
                    <div class="col">
                        <div class="caorusel-box arrow-round gutters-15">
                            <div class="slick-carousel center-mode" data-slick-items="5" data-slick-lg-items="3"  data-slick-md-items="3" data-slick-sm-items="1" data-slick-xs-items="1" data-slick-autoplay='true'>
                                @foreach ($shop->user->products->where('published', 1)->where('featured', 1)->where('digital', 0) as $key => $product)
                                <?php 
                                    $date = (new \Carbon\Carbon)->parse($product->updated_at);
                                    $now = \Carbon\Carbon::now();
                                    $diff = $date->diffInDays($now);
                                ?>
                                @if($diff < 31)
                                    <div class="caorusel-card my-5">
                                        <div class="product-card-2 card card-product shop-cards shop-tech">
                                            <div class="card-body p-0">

                                                <div class="card-image">
                                                    <a href="{{ route('product', $product->slug) }}" class="d-block">
                                                        <img  class="mx-auto img-fit lazyload" src="{{ static_asset('frontend/images/placeholder.jpg') }}" data-src="{{ my_asset($product->thumbnail_img) }}" alt="{{  __($product->name) }}">
                                                    </a>
                                                </div>

                                                <div class="p-3">
                                                    <div class="price-box">
                                                        @if(home_price($product->id) != home_discounted_price($product->id))
                                                        <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                                        <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                                        @else
                                                        <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="star-rating star-rating-sm mt-1">
                                                        {{ renderStarRating($product->rating) }}
                                                    </div>
                                                    <h2 class="product-title p-0 text-truncate-2">
                                                        <a href="{{ route('product', $product->slug) }}">{{  __($product->name) }}</a>
                                                    </h2>
                                                    @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                                                        <div class="club-point mt-2 bg-soft-base-1 border-light-base-1 border">
                                                            {{  translate('Club Point') }}:
                                                            <span class="strong-700 float-right">{{ $product->earn_point }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
    @endif


    <section class="@if (!isset($type)) gry-bg @endif pt-5">
        <div class="container">
            <h4 class="heading-5 strong-600 border-bottom pb-3 mb-4">
                @if (!isset($type))
                    @if($shop->seller_type == 'goods' || $shop->seller_type == 'both')
                    @endif
                    @if($shop->seller_type == 'services')
                    @endif
                @elseif ($type == 'top_selling')
                    {{ translate('Top Selling')}}
                @elseif ($type == 'all_products')
                    {{ translate('All Products')}}
                @elseif ($type == 'all_services')
                {{ translate('All Services')}}
                @endif
            </h4>
            <div class="product-list row gutters-5 sm-no-gutters">
                @php
                    if(!isset($type)){
                        if($shop->seller_type == 'goods' || $shop->seller_type == 'both'){
                            $products = \App\Product::where('user_id', $shop->user->id)->where('published', 1)->where('digital', 0)->orderBy('created_at', 'desc')->paginate(24);
                        }else{
                            $products = \App\Product::where('user_id', $shop->user->id)->where('published', 1)->where('digital', 0)->orderBy('created_at', 'desc')->paginate(24);
                        }
                        if($shop->seller_type == 'services'){
                            $products = \App\Product::where('user_id', $shop->user->id)->where('published', 1)->where('digital', 1)->orderBy('created_at', 'desc')->paginate(24);
                        }else{
                            $products = \App\Product::where('user_id', $shop->user->id)->where('published', 1)->where('digital', 1)->orderBy('created_at', 'desc')->paginate(24); 
                        }
                    }
                    elseif ($type == 'top_selling'){
                        $products = \App\Product::where('user_id', $shop->user->id)->where('published', 1)->orderBy('num_of_sale', 'desc')->paginate(24);
                    }
                    elseif ($type == 'all_products'){
                        $products = \App\Product::where('user_id', $shop->user->id)->where('published', 1)->where('digital', 0)->paginate(24);
                    }
                    elseif ($type == 'all_services'){
                        $products = \App\Product::where('user_id', $shop->user->id)->where('published', 1)->where('digital', 1)->paginate(24);
                    }
                @endphp
                @foreach ($products as $key => $product)
                    <div class="col-xxl-2 col-lg-3 col-md-4 col-6">
                        <div class="card product-box-1 mb-3">
                            <div class="card-image">
                                <a href="{{ route('product', $product->slug) }}" class="d-block text-center">
                                    <img class="img-fit lazyload" src="{{ static_asset('frontend/images/placeholder.jpg') }}" data-src="{{ my_asset($product->thumbnail_img) }}" alt="{{  __($product->name) }}">
                                </a>
                            </div>
                            <div class="card-body p-0">
                                <div class="px-3 py-2">
                                     @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                                        <div class="club-point mb-2 bg-soft-base-1 border-light-base-1 border">
                                            {{  translate('Club Point') }}:
                                            <span class="strong-700 float-right">{{ $product->earn_point }}</span>
                                        </div>
                                    @endif
                                    <h2 class="title text-truncate-2 mb-0">
                                        <a href="{{ route('product', $product->slug) }}">{{  __($product->name) }}</a>
                                    </h2>
                                </div>
                                <div class="price-bar row no-gutters">
                                    <div class="price col-md-7">
                                        @if(home_price($product->id) != home_discounted_price($product->id))
                                            <del class="old-product-price strong-600">{{ home_base_price($product->id) }}</del>
                                            <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                        @else
                                            <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-5">
                                        <div class="star-rating star-rating-sm float-md-right">
                                            {{ renderStarRating($product->rating) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="cart-add d-flex">
                                    <button class="btn add-wishlist border-right" title="Add to Wishlist" onclick="addToWishList({{ $product->id }})">
                                        <i class="la la-heart-o"></i>
                                    </button>
                                    <button class="btn add-compare border-right" title="Add to Compare" onclick="addToCompare({{ $product->id }})">
                                        <i class="la la-refresh"></i>
                                    </button>
                                    <button type="button" class="btn btn-block btn-icon-left" onclick="showAddToCartModal({{ $product->id }})">
                                        <span class="d-none d-sm-inline-block">{{ translate('Add to cart')}}</span><i class="la la-shopping-cart ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col">
                    <div class="products-pagination my-5">
                        <nav aria-label="Center aligned pagination">
                            <ul class="pagination justify-content-center">
                                {{ $products->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
