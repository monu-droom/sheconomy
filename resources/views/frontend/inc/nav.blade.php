<div style="margin-top: 30px;" class="header bg-white">
    <!-- mobile menu -->
    <div class="mobile-side-menu d-lg-none">
        <div class="side-menu-overlay opacity-0" onclick="sideMenuClose()"></div>
        <div class="side-menu-wrap opacity-0">
            <div class="side-menu closed">
                <div class="side-menu-header ">
                    <div class="side-menu-close" onclick="sideMenuClose()">
                        <i class="la la-close"></i>
                    </div>
                    @auth
                    <?php $shop = Auth::user()->shop;?>
                    @endauth  
                    @auth
                        <div class="widget-profile-box px-3 py-4 d-flex align-items-center">
                            @if (Auth::user()->avatar_original != null)
                                <div class="image " style="background-image:url('{{ my_asset(Auth::user()->avatar_original) }}')"></div>
                            @else
                                <div class="image " style="background-image:url('{{ my_asset('frontend/images/user.png') }}')"></div>
                            @endif

                            <div class="name">{{ Auth::user()->name }}</div>
                        </div>
                        <div class="side-login px-3 pb-3">
                            <a href="{{ route('logout') }}">{{translate('Sign Out')}}</a>
                        </div>
                    @else
                        <div class="widget-profile-box px-3 py-4 d-flex align-items-center">
                                <div class="image " style="background-image:url('{{ my_asset('frontend/images/icons/user-placeholder.jpg') }}')"></div>
                        </div>
                        <div class="side-login px-3 pb-3">
                            <a href="{{ route('user.login') }}">{{translate('Sign In')}}</a>
                            <a href="{{ route('user.registration') }}">{{translate('Registration')}}</a>
                        </div>
                    @endauth
                    @auth
                    @php
                    $delivery_viewed = App\Order::where('user_id', Auth::user()->id)->where('delivery_viewed', 0)->get()->count();
                    $payment_status_viewed = App\Order::where('user_id', Auth::user()->id)->where('payment_status_viewed', 0)->get()->count();
                    $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
                    $club_point_addon = \App\Addon::where('unique_identifier', 'club_point')->first();
                    @endphp
                    @endauth
                </div>
                <div class="side-menu-list px-3">
                    <ul class="side-user-menu">
                       <!-- Home Start -->
                       <li>
                            <a href="{{ route('home') }}">
                                <i class="la la-home"></i>
                                <span>{{translate('Home')}}</span>
                            </a>
                        </li>
                       <!-- Home End -->
                        @auth
                        <!-- Dashboard Start -->
                        <li>
                            <a href="{{ route('dashboard') }}"class="{{ areActiveRoutesHome(['dashboard'])}}">
                                <i class="la la-dashboard"></i>
                                <span>{{translate('Dashboard')}}</span>
                            </a>
                        </li>
                        <!-- Dashboard End -->
                        @endauth
                        @auth
                        @if(Auth::User()->user_type == 'seller')
                        <!-- Website Heading Start -->
                        <li>
                            <a data-toggle="collapse" class="{{ areActiveRoutesHome(['shop.basic_info', 'shop.home_settings', 'get.shop.about_us', 'policies', 'shop.contact_us'])}}" href="#website" role="button" aria-expanded="false" aria-controls="collapsesetup">
                                <i class="la la-cog"></i>
                                <span class="category-name">
                                    {{ translate('Website Heading')}}
                                    <i class="dropdown-toggle right"></i>
                                </span>
                            </a>
                        </li>

                        <ul>
                            <div style="margin-top: -20px;" class="collapse" id="website">
                                <li class="ml-4" style="list-style-type: none">
                                <a href="{{route('shop.basic_info')}}" class="{{ areActiveRoutesHome(['shop.basic_info'])}}">
                                        <i class="fa fa-info"></i>
                                        <span class="category-name">
                                            {{ translate('Shop Heading')}}
                                        </span>
                                    </a>
                                </li>
                                <li class="ml-4" style="list-style-type: none">
                                <a href="{{route('shop.home_settings')}}" class="{{ areActiveRoutesHome(['shop.home_settings'])}}">
                                        <i class="fa fa-home"></i>
                                        <span class="category-name">
                                            {{ translate('Homepage')}}
                                        </span>
                                    </a>
                                </li>
                                <li class="ml-4" style="list-style-type: none">
                                <a href="{{route('get.shop.about_us')}}" class="{{ areActiveRoutesHome(['get.shop.about_us'])}}">
                                        <i class="fa fa-address-card"></i>
                                        <span class="category-name">
                                            {{ translate('About Us')}}
                                        </span>
                                    </a>
                                </li>
                                <li class="ml-4" style="list-style-type: none">
                                <a href="{{ route('policies')}}" class="{{ areActiveRoutesHome(['policies'])}}">
                                        <i class="fa fa-info"></i>
                                        <span class="category-name">
                                            {{ translate('Policy')}}
                                        </span>
                                    </a>
                                </li>
                                <li class="ml-4 mb-3" style="list-style-type: none">
                                <a href="{{route('shop.contact_us')}}"  class="{{ areActiveRoutesHome(['shop.contact_us'])}}">
                                        <i class="fa fa-id-badge"></i>
                                        <span class="category-name">
                                            {{ translate('Contact Us')}}
                                        </span>
                                    </a>
                                </li>
                            </div>
                        </ul>
                        @endif
                        <!-- Website Heading End -->
                        @endauth
                        @auth
                        @if(Auth::User()->user_type == 'seller')
                        <!-- Listing Start -->
                        <li style="margin-top: -38px;">
                        <a data-toggle="collapse" class="{{ areActiveRoutesHome(['seller.products', 'seller.products.upload', 'seller.products.edit', 'seller.digitalproducts', 'seller.digitalproducts.upload', 'seller.digitalproducts.edit', 'customer_products.index', 'customer_products.create', 'customer_products.edit', 'product_bulk_upload.index', 'reviews.seller'])}}" href="#listing" role="button" aria-expanded="false" aria-controls="collapsesetup">
                                <i class="fa fa-gear fa-spin"></i>
                                <span class="category-name">
                                    {{ translate('Listing')}}
                                    <i class="dropdown-toggle right"></i>
                                </span>
                            </a>
                        </li>

                        <ul>
                            <div style="margin-top: -20px;" class="collapse" id="listing">
                            @if($shop->seller_type == 'goods' || $shop->seller_type == 'both')
                                <li class="ml-4" style="list-style-type: none">
                                <a href="{{ route('seller.products') }}" class="{{ areActiveRoutesHome(['seller.products', 'seller.products.upload', 'seller.products.edit'])}}">
                                        <i class="la la-diamond"></i>
                                        <span class="category-name">
                                            {{ translate('Goods Listing')}}
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if($shop->seller_type == 'services' || $shop->seller_type == 'both')
                                <li class="ml-4" style="list-style-type: none">
                                <a href="{{ route('seller.digitalproducts') }}" class="{{ areActiveRoutesHome(['seller.digitalproducts', 'seller.digitalproducts.upload', 'seller.digitalproducts.edit'])}}">
                                        <i class="fa fa-tags"></i>
                                        <span class="category-name">
                                            {{ translate('Service Listing')}}
                                        </span>
                                    </a>
                                </li>
                            @endif
                                @if(\App\BusinessSetting::where('type', 'classified_product')->first()->value == 1)
                                <li style="list-style-type: none">
                                    <a href="{{ route('customer_products.index') }}" class="{{ areActiveRoutesHome(['customer_products.index', 'customer_products.create', 'customer_products.edit'])}}">
                                        <i class="la la-diamond"></i>
                                        <span class="category-name">
                                            {{ translate('Classified Product')}}
                                        </span>
                                    </a>
                                </li>
                                @endif
                                <li class="ml-4" style="list-style-type: none">
                                    <a href="{{route('product_bulk_upload.index')}}" class="{{ areActiveRoutesHome(['product_bulk_upload.index'])}}">   
                                        <i class="la la-upload"></i>
                                        <span class="category-name">
                                            {{ translate('Product Bulk Upload')}}
                                        </span>
                                    </a>
                                </li>
                                @auth
                                @php
                                    $review_count = DB::table('reviews')
                                    ->orderBy('code', 'desc')
                                    ->join('products', 'products.id', '=', 'reviews.product_id')
                                    ->where('products.user_id', Auth::user()->id)
                                    ->where('reviews.viewed', 0)
                                    ->select('reviews.id')
                                    ->distinct()
                                    ->count();
                                @endphp
                                @endauth
                                <li class="ml-4 mb-3" style="list-style-type: none">
                                    <a href="{{ route('reviews.seller') }}" class="{{ areActiveRoutesHome(['reviews.seller'])}}">
                                        <i class="la la-star-o"></i>
                                        <span class="category-name">
                                            {{ translate('Product Reviews')}}
                                        </span>
                                    </a>
                                </li>
                            </div>
                        </ul>
                        <!-- Listing End -->
                        @endif
                        @endauth
                        <!-- Orders Start -->
                        @auth
                        @if(Auth::User()->user_type == 'seller')
                            @php
                            $orders = DB::table('orders')
                                ->orderBy('code', 'desc')
                                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                                ->where('order_details.seller_id', Auth::user()->id)
                                ->where('orders.viewed', 0)
                                ->select('orders.id')
                                ->distinct()
                                ->count();
                            @endphp
                        @endauth
                        @endif

                        @auth
                        @if(Auth::User()->user_type == 'seller')
                         <li style="margin-top: -40px;">
                        @endif
                        @endauth
                        <li style="">
                            <a href="{{ route('orders.index') }}" class="{{ areActiveRoutesHome(['orders.index'])}}">
                                <i class="la la-home"></i>
                                <span class="category-name">{{ translate('Orders')}}
                            </a>
                        </li>
                        <!-- Orders End -->
                        @auth
                        @if(Auth::User()->user_type == 'customer')
                        <li style="list-style-type: none">
                            <a href="{{ route('wishlists.index') }}" class="{{ areActiveRoutesHome(['wishlists.index'])}}">
                                <i class="la la-heart-o"></i>
                                <span class="category-name">
                                    {{ translate('WishList')}}
                                </span>
                            </a>
                        </li>
                        @endauth
                        @auth
                        @elseif(Auth::User()->user_type == 'seller')
                        @endauth
                        @else
                        <li style="list-style-type: none">
                            <a href="{{ route('wishlists.index') }}" class="{{ areActiveRoutesHome(['wishlists.index'])}}">
                                <i class="la la-heart-o"></i>
                                <span class="category-name">
                                    {{ translate('WishList')}}
                                </span>
                            </a>
                        </li>
                        @endif

                        @auth
                        @if(Auth::User()->user_type == 'customer')
                        <li style="list-style-type: none">
                            <a href="{{ route('purchase_history.index') }}" class="{{ areActiveRoutesHome(['wishlists.index'])}}">
                                <i class="la la-heart-o"></i>
                                <span class="category-name">
                                    {{ translate('Order History')}}
                                </span>
                            </a>
                        </li>
                        @elseif(Auth::User()->user_type == 'seller')
                        @endauth
                        @else

                        @endif
                        

                        @auth
                        <!-- Refund Start -->
                        @php
                        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
                        $club_point_addon = \App\Addon::where('unique_identifier', 'club_point')->first();
                        @endphp
                        <li>
                            <a data-toggle="collapse" class="{{ areActiveRoutesHome(['customer_refund_request', 'vendor_refund_request'])}}" href="#refunds" role="button" aria-expanded="false" aria-controls="collapsesetup">
                                <i class="fa fa-credit-card"></i>
                                <span class="category-name">
                                    {{ translate('Refund')}}
                                    <i class="dropdown-toggle right"></i>
                                </span>
                            </a>
                        </li>
                        <ul>
                            <div style="margin-top: -20px;" class="collapse" id="refunds">
                            @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                                <li class="ml-4" style="list-style-type: none">
                                    <a href="{{ route('customer_refund_request') }}" class="{{ areActiveRoutesHome(['customer_refund_request'])}}">
                                        <i class="fa fa-paper-plane"></i>
                                        <span class="category-name">
                                            {{ translate('Sent Request')}}
                                        </span>
                                    </a>
                                </li>
                                <li class="ml-4 mb-3" style="list-style-type: none">
                                    <a href="{{ route('vendor_refund_request') }}" class="{{ areActiveRoutesHome(['vendor_refund_request'])}}">
                                        <i class="fa fa-bookmark"></i>
                                        <span class="category-name">
                                            {{ translate('Recieved Request')}}
                                        </span>
                                    </a>
                                </li>
                                @endif
                            </div>
                        </ul>
                        <!-- Refund End -->
                        @endauth
                        @if (\App\Addon::where('unique_identifier', 'pos_system')->first() != null && \App\Addon::where('unique_identifier', 'pos_system')->first()->activated)
                            @if (\App\BusinessSetting::where('type', 'pos_activation_for_seller')->first() != null && \App\BusinessSetting::where('type', 'pos_activation_for_seller')->first()->value != 0)
                                <li>
                                    <a href="{{route('poin-of-sales.seller_index')}}" class="{{ areActiveRoutesHome(['poin-of-sales.seller_index'])}}">
                                        <i class="la la-fax"></i>
                                        <span class="category-name">
                                            {{ translate('POS Manager')}}
                                        </span>
                                    </a>
                                </li>
                            @endif
                        @endif  

                        <!-- Conversations Start -->
                        @auth
                        @if (\App\BusinessSetting::where('type', 'conversation_system')->first()->value == 1)
                        @php
                            $conversation_sent = \App\Conversation::where('sender_id', Auth::user()->id)->where('sender_viewed', 0)->get();
                            $conversation_recieved = \App\Conversation::where('receiver_id', Auth::user()->id)->where('receiver_viewed', 0)->get();
                        @endphp
                        @endauth
                        <li style="margin-top: -40px;">
                            <a href="{{ route('conversations.index') }}" class="{{ areActiveRoutesHome(['conversations.index', 'conversations.show'])}}">
                                <i class="la la-comment"></i>
                               <span class="category-name">
                                {{ translate('Conversations')}}
                                @if (count($conversation_sent)+count($conversation_recieved) > 0)
                                    <span class="ml-2" style="color:green"><strong>({{ count($conversation_sent)+count($conversation_recieved) }})</strong></span>
                                @endif
                            </span>
                            </a>
                        </li>
                        @endif
                        <!-- Conversation End -->

                        @if (\App\BusinessSetting::where('type', 'wallet_system')->first()->value == 1)
                            <li>
                                <a href="{{ route('wallet.index') }}" class="{{ areActiveRoutesHome(['wallet.index'])}}">
                                    <i class="la la-dollar"></i>
                                    <span class="category-name">
                                        {{ translate('My Wallet')}}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated && Auth::user()->affiliate_user != null && Auth::user()->affiliate_user->status)
                            <li>
                                <a href="{{ route('affiliate.user.index') }}" class="{{ areActiveRoutesHome(['affiliate.user.index', 'affiliate.payment_settings'])}}">
                                    <i class="la la-dollar"></i>
                                    <span class="category-name">
                                        {{ translate('Affiliate System')}}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @auth
                        @if ($club_point_addon != null && $club_point_addon->activated == 1)
                            <li>
                                <a href="{{ route('earnng_point_for_user') }}" class="{{ areActiveRoutesHome(['earnng_point_for_user'])}}">
                                    <i class="la la-dollar"></i>
                                    <span class="category-name">
                                        {{ translate('Earning Points')}}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @endauth
                        @auth
                        @if(Auth::User()->user_type == 'seller')
                        <!-- My Shopping Start -->
                        <li>
                            <a data-toggle="collapse" class="{{ areActiveRoutesHome(['wishlists.index', 'digital_purchase_history.index', 'payments.index', 'purchase_history.index'])}}" href="#shopping" role="button" aria-expanded="false" aria-controls="collapsesetup">
                                <i class="fa fa-briefcase"></i>
                                <span class="category-name">
                                    {{ translate('My Shoppings')}}
                                    <i class="dropdown-toggle right"></i>
                                </span>
                            </a>
                        </li>
                        <ul>
                            <div style="margin-top: -20px;" class="collapse" id="shopping">
                                <li class="ml-4" style="list-style-type: none">
                                    <a href="{{ route('wishlists.index') }}" class="{{ areActiveRoutesHome(['wishlists.index'])}}">
                                        <i class="la la-heart-o"></i>
                                        <span class="category-name">
                                            {{ translate('WishList')}}
                                        </span>
                                    </a>
                                </li>
                                <li class="ml-4" style="list-style-type: none">
                                    <a href="{{ route('digital_purchase_history.index') }}" class="{{ areActiveRoutesHome(['digital_purchase_history.index'])}}">
                                        <i class="la la-download"></i>
                                        <span class="category-name">
                                            {{ translate('Downloads')}}
                                        </span>
                                    </a>
                                </li>
                                <li class="ml-4" style="list-style-type: none">
                                    <a href="{{ route('payments.index') }}" class="{{ areActiveRoutesHome(['payments.index'])}}">
                                        <i class="la la-cc-mastercard"></i>
                                        <span class="category-name">
                                            {{ translate('Payment History')}}
                                        </span>
                                    </a>
                                </li>
                                <li class="ml-4 mb-3" style="list-style-type: none">
                                    <a href="{{ route('purchase_history.index') }}" class="{{ areActiveRoutesHome(['purchase_history.index'])}}">
                                        <i class="la la-file-text"></i>
                                        <span class="category-name">{{ translate('Purchase History')}}</span>
                                    </a>
                                </li>
                            </div>
                        </ul>
                        <!-- My Shopping End -->
                        @endif
                        @endauth
                        @auth
                        @if(Auth::User()->user_type == 'seller')
                        <!-- Settings Start -->
                        <li style="margin-top: -40px;">
                            <a data-toggle="collapse" class="{{ areActiveRoutesHome(['get.payment.setup', 'get.domain.setup', 'get.shipping.setup', 'kyc', 'profile' ])}}" href="#settings" role="button" aria-expanded="false" aria-controls="collapsesetup">
                                <i class="fa fa-briefcase"></i>
                                <span class="category-name">
                                    {{ translate('Settings')}}
                                    <i class="dropdown-toggle right"></i>
                                </span>
                            </a>
                        </li>
                        <ul>
                            <div style="margin-top: -20px;" class="collapse" id="settings">
                                <li class="ml-4" style="list-style-type: none">
                                    <a href="{{route('get.payment.setup')}}" class="{{ areActiveRoutesHome(['get.payment.setup'])}}">
                                        <i class="fa fa-credit-card"></i>
                                        <span class="category-name">
                                            {{ translate('Payment Setup')}}
                                        </span>
                                    </a>
                                </li>
                                <li class="ml-4" style="list-style-type: none">
                                    <a href="{{route('get.domain.setup')}}" class="{{ areActiveRoutesHome(['get.domain.setup'])}}">
                                        <i class="fa fa-globe"></i>
                                        <span class="category-name">
                                            {{ translate('Domain Setup')}}
                                        </span>
                                    </a>
                                </li>
                                @if($shop->seller_type == 'goods' || $shop->seller_type == 'both')
                                <li class="ml-4" style="list-style-type: none">
                                    <a href="{{route('get.shipping.setup')}}" class="{{ areActiveRoutesHome(['get.shipping.setup'])}}"  >
                                        <i class="fa fa-shopping-cart"></i>
                                        <span class="category-name">
                                            {{ translate('Shipping Setup')}}
                                        </span>
                                    </a>
                                </li>
                                @endif
                                <li class="ml-4" style="list-style-type: none">
                                    <a href="{{route('kyc')}}" class="{{ areActiveRoutesHome(['kyc'])}}">
                                        <i class="fa fa-check"></i>
                                        <span class="category-name">
                                            {{ translate('Document Verification')}}
                                        </span>
                                    </a>
                                </li>
                                <li class="ml-4 mb-3" style="list-style-type: none">
                                    <a href="{{route('profile')}}" class="{{ areActiveRoutesHome(['profile'])}}"  >
                                        <i class="fa fa-user"></i>
                                        <span class="category-name">
                                            {{ translate('Manage Profile')}}
                                        </span>
                                    </a>
                                </li>
                            </div>
                        </ul>
                        <!-- Settings End -->
                        @endif
                        @endauth
                        <!-- Support Ticket Start -->
                        @auth
                        @php
                    $support_ticket = DB::table('tickets')
                                        ->where('client_viewed', 0)
                                        ->where('user_id', Auth::user()->id)
                                        ->count();
                        @endphp
                        @if(Auth::User()->user_type == 'seller')
                        <li style="margin-top: -40px;">
                        @else
                        <li>
                        @endif
                            <a href="{{ route('support_ticket.index') }}" class="{{ areActiveRoutesHome(['support_ticket.index', 'support_ticket.show'])}}">
                                <i class="la la-support"></i>
                                <span class="category-name">
                                    {{ translate('Support Ticket')}} 
                                </span>
                            </a>
                        </li>
                        <!-- Support Ticket End -->
                        @endauth
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- end mobile menu -->

    <div style="margin-top: 20px;" class="position-relative logo-bar-area">
        <div class="">
            <div class="container">
                <div class="row no-gutters align-items-center">
                    <div class="col-lg-3 col-8">
                        <div class="d-flex">
                            <div class="d-block d-lg-none mobile-menu-icon-box">
                                <!-- Navbar toggler  -->
                                <a href="" onclick="sideMenuOpen(this)">
                                    <div class="hamburger-icon">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </a>
                            </div>

                            <!-- Brand/Logo -->
                            <?php 
                                $local = env('SERVER', '');
                            ?> 
                            @if($local == 'production')
                                <a class="navbar-brand w-100" href="https://sheconomy.in">
                            @else
                                <a class="navbar-brand w-100" href="http://localhost">
                            @endif
                                @php
                                    $generalsetting = \App\GeneralSetting::first();
                                @endphp
                                @if($generalsetting->logo != null)
                                    <img src="{{ my_asset($generalsetting->logo) }}" alt="{{ env('APP_NAME') }}">
                                @else
                                    <img src="{{ my_asset('frontend/images/logo/logo.png') }}" alt="{{ env('APP_NAME') }}">
                                @endif
                            </a>

                            @if(Route::currentRouteName() != 'home' && Route::currentRouteName() != 'categories.all')
                                <div class="d-none d-xl-block category-menu-icon-box">
                                    <div class="dropdown-toggle navbar-light category-menu-icon" id="category-menu-icon">
                                        <span class="navbar-toggler-icon"></span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-9 col-4 position-static">
                        <div class="d-flex w-100">
                            <div class="search-box flex-grow-1 px-4">
                                <form action="{{ route('search') }}" method="GET">
                                    <div class="d-flex position-relative">
                                        <div class="d-lg-none search-box-back">
                                            <button class="" type="button"><i class="la la-long-arrow-left"></i></button>
                                        </div>
                                        <div class="w-100">
                                            <input type="text" aria-label="Search" id="search" name="q" class="w-100" placeholder="{{translate('I am shopping for...')}}" autocomplete="off">
                                        </div>
                                        <div class="form-group category-select d-none d-xl-block">
                                            <select class="form-control selectpicker" name="category">
                                                <option value="">{{translate('All Categories')}}</option>
                                                @foreach (\App\Category::all() as $key => $category)
                                                <option value="{{ $category->slug }}"
                                                    @isset($category_id)
                                                        @if ($category_id == $category->id)
                                                            selected
                                                        @endif
                                                    @endisset
                                                    >{{ __($category->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button class="d-none d-lg-block" type="submit">
                                            <i class="la la-search la-flip-horizontal"></i>
                                        </button>
                                        <div class="typed-search-box d-none">
                                            <div class="search-preloader">
                                                <div class="loader"><div></div><div></div><div></div></div>
                                            </div>
                                            <div class="search-nothing d-none">

                                            </div>
                                            <div id="search-content">

                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>

                            <div class="logo-bar-icons d-inline-block ml-auto">
                                <div class="d-inline-block d-lg-none">
                                    <div class="nav-search-box">
                                        <a href="#" class="nav-box-link">
                                            <i class="la la-search la-flip-horizontal d-inline-block nav-box-icon"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="d-none d-lg-inline-block">
                                   <ul class="inline-links d-lg-inline-block d-flex justify-content-between">
                                        <li class="dropdown" id="currency-change">
                                            @php
                                                if(Session::has('currency_code')){
                                                    $currency_code = Session::get('currency_code');
                                                }
                                                else{
                                                    $currency_code = \App\Currency::findOrFail(\App\BusinessSetting::where('type', 'system_default_currency')->first()->value)->code;
                                                }
                                            @endphp
                                            <a href="" class="dropdown-toggle top-bar-item" data-toggle="dropdown">
                                                {{ \App\Currency::where('code', $currency_code)->first()->name }} {{ (\App\Currency::where('code', $currency_code)->first()->symbol) }}
                                            </a>
                                            <ul class="dropdown-menu">
                                                @foreach (\App\Currency::where('status', 1)->get() as $key => $currency)
                                                    <li class="dropdown-item @if($currency_code == $currency->code) active @endif">
                                                        <a href="" data-currency="{{ $currency->code }}">{{ $currency->name }} ({{ $currency->symbol }})</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                   </ul>
                                </div>
                                <div class="d-none d-lg-inline-block">
                                   <ul class="inline-links d-lg-inline-block d-flex justify-content-between">
                                        @auth
                                        <li class="dropdown">
                                            <a href="" class="dropdown-toggle top-bar-item" data-toggle="dropdown">
                                              {{ Auth::user()->name }}  
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="dropdown-item">
                                                    <a href="{{ route('dashboard') }}">{{ translate('My Profile')}}</a>     
                                                </li>
                                                <li class="dropdown-item">
                                                    <a href="{{ route('purchase_history.index') }}">{{ translate('Purchase History')}}</a>     
                                                </li>
                                                <li class="dropdown-item">
                                                    <a href="{{ route('wishlists.index') }}">{{ translate('Wishlist')}}</a>     
                                                </li>
                                                <li class="dropdown-item">
                                                    <a href="{{ route('logout') }}">{{ translate('Logout')}}</a>     
                                                </li>
                                            </ul>
                                        </li>
                                        @else
                                        <li>
                                            <a href="{{ route('user.login') }}" class="login-btn">
                                              <i class="la la-user d-inline-block"></i>
                                              {{ translate('Login')}}  
                                            </a>
                                        </li>
                                        @endauth
                                   </ul> 
                                </div>
                                <div class="d-inline-block" data-hover="dropdown">
                                    <div class="nav-cart-box dropdown" id="cart_items">
                                        <a href="" class="nav-box-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="la la-shopping-cart d-inline-block nav-box-icon"></i>
                                            <span class="nav-box-text d-none d-xl-inline-block">{{translate('Cart')}}</span>
                                            <?php $carts = \App\Cart::where('user_id', Auth::id())->get(); $cart_count = $carts->count();?>
                                            @if($carts)
                                                <span class="nav-box-number">{{ $cart_count }}</span>
                                            @else
                                                <span class="nav-box-number">0</span>
                                            @endif
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right px-0">
                                            <li>
                                                <div class="dropdown-cart px-0">
                                                    @if($carts)
                                                        @if($cart_count > 0)
                                                            <div class="dc-header">
                                                                <h3 class="heading heading-6 strong-700">{{translate('Cart Items')}}</h3>
                                                            </div>
                                                            <div class="dropdown-cart-items c-scrollbar">
                                                                @php
                                                                    $total = 0;
                                                                @endphp
                                                                @foreach($carts as $cart)
                                                                    @php
                                                                        $product = \App\Product::find($cart->product_id);
                                                                        $total = $total + $cart->price * $cart->quantity;
                                                                    @endphp
                                                                    <div class="dc-item">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="dc-image">
                                                                                <a href="{{ route('product', $product->slug) }}">
                                                                                    <img src="{{ my_asset('frontend/images/placeholder.jpg') }}" data-src="{{ my_asset($product->thumbnail_img) }}" class="img-fluid lazyload" alt="{{ __($product->name) }}">
                                                                                </a>
                                                                            </div>
                                                                            <div class="dc-content">
                                                                                <span class="d-block dc-product-name text-capitalize strong-600 mb-1">
                                                                                    <a href="{{ route('product', $product->slug) }}">
                                                                                        {{ __($product->name) }}
                                                                                    </a>
                                                                                </span>

                                                                                <span class="dc-quantity">x{{ $cart->quantity }}</span>
                                                                                <span class="dc-price">{{ single_price($cart->price * $cart->quantity) }}</span>
                                                                            </div>
                                                                            <div class="dc-actions">
                                                                                <button onclick="removeFromCart({{ $key }}, {{ $product['id'] }})">
                                                                                    <i class="la la-close"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="dc-item py-3">
                                                                <span class="subtotal-text">{{translate('Subtotal')}}</span>
                                                                <span class="subtotal-amount">{{ single_price($total) }}</span>
                                                            </div>
                                                            <div class="py-2 text-center dc-btn">
                                                                <ul class="inline-links inline-links--style-3">
                                                                    <li class="px-1">
                                                                        <a href="{{ route('cart') }}" class="link link--style-1 text-capitalize btn btn-base-1 px-3 py-1">
                                                                            <i class="la la-shopping-cart"></i> {{translate('View cart')}}
                                                                        </a>
                                                                    </li>
                                                                    @if (Auth::check())
                                                                    <li class="px-1">
                                                                        <a href="{{ route('checkout.shipping_info') }}" class="link link--style-1 text-capitalize btn btn-base-1 px-3 py-1 light-text">
                                                                            <i class="la la-mail-forward"></i> {{translate('Checkout')}}
                                                                        </a>
                                                                    </li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        @else
                                                            <div class="dc-header">
                                                                <h3 class="heading heading-6 strong-700">{{translate('Your Cart is empty')}}</h3>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="dc-header">
                                                            <h3 class="heading heading-6 strong-700">{{translate('Your Cart is empty')}}</h3>
                                                        </div>
                                                    @endif
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hover-category-menu" id="hover-category-menu">
            <div class="container">
                <div class="row no-gutters position-relative">
                    <div class="col-lg-3 position-static">
                        <div class="category-sidebar" id="category-sidebar">
                            <div class="all-category">
                                <span>{{translate('CATEGORIES')}}</span>
                                <a href="{{ route('categories.all') }}" class="d-inline-block">{{ translate('See All') }} ></a>
                            </div>
                            <ul class="categories">
                                @foreach (\App\Category::all()->take(11) as $key => $category)
                                    @php
                                        $brands = array();
                                    @endphp
                                    <li class="category-nav-element" data-id="{{ $category->id }}">
                                        <a href="{{ route('products.category', $category->slug) }}">
                                            <img class="cat-image lazyload" src="{{ my_asset('frontend/images/placeholder.jpg') }}" data-src="{{ my_asset($category->icon) }}" width="30" alt="{{ __($category->name) }}">
                                            <span class="cat-name">{{ __($category->name) }}</span>
                                        </a>
                                        @if(count($category->subcategories)>0)
                                            <div class="sub-cat-menu c-scrollbar">
                                                <div class="c-preloader">
                                                    <i class="fa fa-spin fa-spinner"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Navbar -->

    <!-- <div class="main-nav-area d-none d-lg-block">
        <nav class="navbar navbar-expand-lg navbar--bold navbar--style-2 navbar-light bg-default">
            <div class="container">
                <div class="collapse navbar-collapse align-items-center justify-content-center" id="navbar_main">
                    <ul class="navbar-nav">
                        @foreach (\App\Search::orderBy('count', 'desc')->get()->take(5) as $key => $search)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('suggestion.search', $search->query) }}">{{ $search->query }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </nav>
    </div> -->
</div>  