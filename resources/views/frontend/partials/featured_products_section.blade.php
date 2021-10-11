<section class="mb-4">
    <div class="container">
        <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
            <div class="section-title-1 clearfix">
                <h3 class="heading-5 strong-700 mb-0 float-left">
                    <span class="mr-4">{{ translate('Featured Products')}}</span>
                </h3>
            </div>
            <?php 
            $prod_goods = \App\Product::select('products.*')
                ->join('product_factor as pf', 'products.id', '=', 'pf.product_id')
                ->where('pf.availiblity', 1)
                ->where('products.published', 1)
                ->where('products.featured', 1)
                ->where('products.digital', 0)  
                ->whereIn('products.user_id', $all_prod_users)                    
                ->orderBy('pf.clicks', 'DESC')           
                ->orderBy('pf.view_time', 'DESC')           
                ->orderBy('pf.rating', 'DESC')           
                ->orderBy('pf.share', 'DESC')           
                ->orderBy('pf.price', 'ASC');
            $prods = json_decode($prod_goods->get(), true);
            if(empty($prods)){
                $prod_goods = \App\Product::select('products.*')
                    ->join('product_factor as pf', 'products.id', '=', 'pf.product_id')
                    ->where('pf.availiblity', 1)
                    ->where('products.published', 1)
                    ->where('products.featured', 1)
                    ->where('products.digital', 0)  
                    ->orWhereIn('products.user_id', $all_prod_users)                    
                    ->orderBy('pf.clicks', 'DESC')           
                    ->orderBy('pf.view_time', 'DESC')           
                    ->orderBy('pf.rating', 'DESC')           
                    ->orderBy('pf.share', 'DESC')           
                    ->orderBy('pf.price', 'ASC');
            }
            ?>
            <div class="caorusel-box arrow-round gutters-5">
                <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4"  data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                    @foreach (filter_products($prod_goods)->limit(12)->get() as $key => $product)
                    <div class="caorusel-card">
                        <div class="product-card-2 card card-product shop-cards shop-tech">
                            <div class="card-body p-0">

                                <div class="card-image">
                                    <a href="{{ route('product', $product->slug) }}" class="d-block">
                                        <img class="img-fit lazyload mx-auto" src="{{ static_asset('frontend/images/placeholder.jpg') }}" data-src="{{ my_asset($product->thumbnail_img) }}" alt="{{ __($product->name) }}">
                                    </a>
                                </div>

                                <div class="p-md-3 p-2">
                                    <div class="price-box">
                                        @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                            <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                        @endif
                                        <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                    </div>
                                    <div class="star-rating star-rating-sm mt-1">
                                        {{ renderStarRating($product->rating) }}
                                    </div>
                                    <h2 class="product-title p-0">
                                        <a href="{{ route('product', $product->slug) }}" class="text-truncate">{{ __($product->name) }}</a>
                                    </h2>

                                    @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                                        <div class="club-point mt-2 bg-soft-base-1 border-light-base-1 border">
                                            {{ translate('Club Point') }}:
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
</section>

<section class="mb-4">
    <div class="container">
        <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
            <div class="section-title-1 clearfix">
                <h3 class="heading-5 strong-700 mb-0 float-left">
                    <span class="mr-4">{{ translate('Featured Services')}}</span>
                </h3>
            </div>
            <?php 
            $prod_services = \App\Product::select('products.*')
                ->join('product_factor as pf', 'products.id', '=', 'pf.product_id')
                ->where('pf.availiblity', 1)
                ->where('products.published', 1)
                ->where('products.featured', 1)
                ->where('products.digital', 1)
                ->whereIn('products.user_id', $all_service_users)                      
                ->orderBy('pf.clicks', 'DESC')           
                ->orderBy('pf.view_time', 'DESC')           
                ->orderBy('pf.rating', 'DESC')           
                ->orderBy('pf.share', 'DESC')           
                ->orderBy('pf.price', 'ASC');
                $servs = json_decode($prod_services->get(), true);
                if(empty($servs)){
                    $prod_services = \App\Product::select('products.*')
                        ->join('product_factor as pf', 'products.id', '=', 'pf.product_id')
                        ->where('pf.availiblity', 1)
                        ->where('products.published', 1)
                        ->where('products.featured', 1)
                        ->where('products.digital', 1)
                        ->orWhereIn('products.user_id', $all_service_users)                      
                        ->orderBy('pf.clicks', 'DESC')           
                        ->orderBy('pf.view_time', 'DESC')           
                        ->orderBy('pf.rating', 'DESC')           
                        ->orderBy('pf.share', 'DESC')           
                        ->orderBy('pf.price', 'ASC');
                }
            ?>
            <div class="caorusel-box arrow-round gutters-5">
                <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4"  data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                    @foreach (filter_products($prod_services)->limit(12)->get() as $key => $product)
                    <div class="caorusel-card">
                        <div class="product-card-2 card card-product shop-cards shop-tech">
                            <div class="card-body p-0">

                                <div class="card-image">
                                    <a href="{{ route('product', $product->slug) }}" class="d-block">
                                        <img class="img-fit lazyload mx-auto" src="{{ static_asset('frontend/images/placeholder.jpg') }}" data-src="{{ my_asset($product->thumbnail_img) }}" alt="{{ __($product->name) }}">
                                    </a>
                                </div>

                                <div class="p-md-3 p-2">
                                    <div class="price-box">
                                        @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                            <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                        @endif
                                        <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                    </div>
                                    <div class="star-rating star-rating-sm mt-1">
                                        {{ renderStarRating($product->rating) }}
                                    </div>
                                    <h2 class="product-title p-0">
                                        <a href="{{ route('product', $product->slug) }}" class="text-truncate">{{ __($product->name) }}</a>
                                    </h2>

                                    @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                                        <div class="club-point mt-2 bg-soft-base-1 border-light-base-1 border">
                                            {{ translate('Club Point') }}:
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
</section>

