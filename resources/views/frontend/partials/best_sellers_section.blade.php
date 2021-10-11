@if (\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
    @php
        $clientIp = \Request::getClientIp();
        $location = session()->get('location');
        $user_country = $location->countryName;
        if($user_country == 'india'){
            $sellers = \App\Seller::join('seller_rating as sr', 'sellers.id', '=', 'sr.seller_id')
                    ->join('order_details as od', 'sellers.user_id', '=', 'od.seller_id')
                    ->join('shops', 'shops.user_id', '=', 'sellers.user_id')
                    ->selectRaw('sellers.*, count(od.id) AS `od_count`, count(sr.id) AS `sr_count`,
                                SUM(sr.rating_seller) as `seller_rating`')
                    ->where('od.payment_status', 'paid')
                    ->where('verification_status', 1)
                    ->where('shops.sell_in', strtolower($user_country))
                    ->groupBy('sellers.id')         
                    ->orderBy('sr.rating_seller', 'DESC')
                    ->orderBy('od_count', 'DESC');
        }else{
            $sellers = \App\Seller::join('seller_rating as sr', 'sellers.id', '=', 'sr.seller_id')
                    ->join('order_details as od', 'sellers.id', '=', 'od.seller_id')
                    ->selectRaw('sellers.*, count(od.id) AS `od_count`, count(sr.id) AS `sr_count`,
                                SUM(sr.rating_seller) as `seller_rating`')
                    ->where('od.payment_status', 'paid')
                    ->where('verification_status', 1)
                    ->groupBy('sellers.id')         
                    ->orderBy('sr.rating_seller', 'DESC')
                    ->orderBy('od_count', 'DESC');            
        }
    @endphp
    @if(!empty($sellers))
        <section class="mb-5">
        <div class="container">
            <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
                <div class="section-title-1 clearfix">
                    <h3 class="heading-5 strong-700 mb-0 float-left">
                        <span class="mr-4">{{ translate('Best Sellers')}}</span>
                    </h3>
                    <ul class="inline-links float-right">
                        <li><a  class="active">{{ translate('Top 20')}}</a></li>
                    </ul>
                </div>
                <div class="caorusel-box arrow-round gutters-5">
                    <div class="slick-carousel" data-slick-items="3" data-slick-lg-items="3"  data-slick-md-items="2" data-slick-sm-items="2" data-slick-xs-items="1" data-slick-rows="2">
                    @foreach($sellers->take(20)->get() as $key => $seller)  
                        <div class="caorusel-card my-1">
                            <div class="row no-gutters box-3 align-items-center border">
                                <div class="col-4">
                                    <a href="{{ route('shop.visit', $seller->user->shop->slug) }}" class="d-block product-image p-3">
                                        <img
                                            src="{{ static_asset('frontend/images/placeholder.jpg') }}"
                                            data-src="@if ($seller->user->shop->logo !== null) {{ my_asset($seller->user->shop->logo) }} @else {{ static_asset('frontend/images/placeholder.jpg') }} @endif"
                                            alt="{{ $seller->user->shop->name }}"
                                            class="img-fluid lazyload"
                                        >
                                    </a>
                                </div>
                                <div class="col-8 border-left">
                                    <div class="p-3">
                                        <h2 class="product-title mb-0 p-0 text-truncate">
                                            <a href="{{ route('shop.visit', $seller->user->shop->slug) }}">{{ translate($seller->user->shop->name) }}</a>
                                        </h2>
                                        <div class="star-rating star-rating-sm mb-2">
                                        <?php 
                                            $total = 0;
                                            $total = $total + $seller->sr_count;                                        
                                        ?>
                                            @if ($total > 0)
                                                {{ renderStarRating($seller->seller_rating/$total) }}
                                            @else
                                                {{ renderStarRating(0) }}
                                            @endif
                                        </div>
                                        <div class="">
                                            <a href="{{ route('shop.visit', $seller->user->shop->slug) }}" class="icon-anim">
                                                {{ translate('Visit Store') }} <i class="la la-angle-right text-sm"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
@endif
