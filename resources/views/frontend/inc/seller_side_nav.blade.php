
<div class="sidebar sidebar--style-3 no-border stickyfill p-0">
    <div class="widget mb-0">
        <div class="widget-profile-box text-center p-3">
            @if (Auth::user()->avatar_original != null)
                <div class="image" style="background-image:url('{{ my_asset(Auth::user()->avatar_original) }}')"></div>
            @else
                <img src="{{ my_asset('frontend/images/user.png') }}" class="image rounded-circle">
            @endif
            @if(Auth::user()->seller->verification_status == 1)
                <div class="name mb-0">{{ Auth::user()->name }} <span class="ml-2"><i class="fa fa-check-circle" style="color:green"></i></span></div>
            @else
                <div class="name mb-0">{{ Auth::user()->name }} <span class="ml-2"><i class="fa fa-times-circle" style="color:red"></i></span></div>
            @endif
        </div>
        <div class="sidebar-widget-title py-3">
            <span>{{ translate('Menu')}}</span>
        </div>
        <div class="widget-profile-menu py-3">
            <ul class="categories categories--style-3">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ areActiveRoutesHome(['dashboard'])}}">
                        <i class="la la-dashboard"></i>
                        <span class="category-name">
                            {{ translate('Dashboard')}}
                        </span>
                    </a>
                </li>
                @php
                    $delivery_viewed = App\Order::where('user_id', Auth::user()->id)->where('delivery_viewed', 0)->get()->count();
                    $payment_status_viewed = App\Order::where('user_id', Auth::user()->id)->where('payment_status_viewed', 0)->get()->count();
                    $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
                    $club_point_addon = \App\Addon::where('unique_identifier', 'club_point')->first();
                @endphp
                
                <!-- Website setting start -->
                <li>
                    <a data-toggle="collapse" class="{{ areActiveRoutesHome(['shop.basic_info', 'shop.home_settings', 'get.shop.about_us', 'policies', 'shop.contact_us'])}}" href="#shopsettings" role="button" aria-expanded="false" aria-controls="collapsesetup">
                        <i class="la la-cog"></i>
                        <span class="category-name">
                            {{ translate('Website Heading')}}
                            <i class="dropdown-toggle right"></i>
                        </span>
                    </a>
                </li>

                <ul>
                <div class="collapse" id="shopsettings">
                
                <li style="list-style-type: none;">
                   
                    <a href="{{route('shop.basic_info')}}" class="{{ areActiveRoutesHome(['shop.basic_info'])}}">
                        <i class="fa fa-info"></i>
                        <span class="category-name">
                            {{ translate('Shop Heading')}}
                        </span>
                    </a>
                </li>

                <li style="list-style-type: none;">
                   
                    <a href="{{route('shop.home_settings')}}" class="{{ areActiveRoutesHome(['shop.home_settings'])}}">
                        <i class="fa fa-home"></i>
                        <span class="category-name">
                            {{ translate('Homepage')}}
                        </span>
                    </a>
                </li>

                <li style="list-style-type: none;">
                   
                    <a href="{{route('get.shop.about_us')}}" class="{{ areActiveRoutesHome(['get.shop.about_us'])}}">
                        <i class="fa fa-address-card"></i>
                        <span class="category-name">
                            {{ translate('About Us')}}
                        </span>
                    </a>
                </li>

                <li style="list-style-type: none;">
                   
                   <a href="{{ route('policies')}}" class="{{ areActiveRoutesHome(['policies'])}}">
                       <i class="fa fa-info"></i>
                       <span class="category-name">
                           {{ translate('Policy')}}
                       </span>
                   </a>
               </li>

                <li style="list-style-type: none;">
                   
                    <a href="{{route('shop.contact_us')}}"  class="{{ areActiveRoutesHome(['shop.contact_us'])}}">
                        <i class="fa fa-id-badge"></i>
                        <span class="category-name">
                            {{ translate('Contact Us')}}
                        </span>
                    </a>
                </li>

                </div>
                </ul>
                <!-- Website setting end -->

                <!-- Listing start -->
                <li>
                    <a data-toggle="collapse" class="{{ areActiveRoutesHome(['seller.products', 'seller.products.upload', 'seller.products.edit', 'seller.digitalproducts', 'seller.digitalproducts.upload', 'seller.digitalproducts.edit', 'customer_products.index', 'customer_products.create', 'customer_products.edit', 'product_bulk_upload.index', 'reviews.seller'])}}" href="#listingsetup" role="button" aria-expanded="false" aria-controls="collapsesetup">
                        <i class="fa fa-gear fa-spin"></i>
                        <span class="category-name">
                            {{ translate('Listing')}}
                            <i class="dropdown-toggle right"></i>
                        </span>
                    </a>
                </li>
                <ul>
                    <div class="collapse" id="listingsetup">
                    <?php $shop = Auth::user()->shop;?>
                    @if($shop->seller_type == 'goods' || $shop->seller_type == 'both')
                        <li style="list-style-type: none"> 
                            <a href="{{ route('seller.products') }}" class="{{ areActiveRoutesHome(['seller.products', 'seller.products.upload', 'seller.products.edit'])}}">
                                <i class="la la-diamond"></i>
                                <span class="category-name">
                                    {{ translate('Goods Listing')}}
                                </span>
                            </a>
                        </li>
                    @endif
                    @if($shop->seller_type == 'services' || $shop->seller_type == 'both')
                        <li style="list-style-type: none">
                            <a href="{{ route('seller.digitalproducts') }}" class="{{ areActiveRoutesHome(['seller.digitalproducts', 'seller.digitalproducts.upload', 'seller.digitalproducts.edit'])}}">
                                <i class="fa fa-tags"></i>
                                <span class="category-name">
                                    {{ translate('Services Listing')}}
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
                        <li style="list-style-type: none">
                            <a href="{{route('product_bulk_upload.index')}}" class="{{ areActiveRoutesHome(['product_bulk_upload.index'])}}">
                                <i class="la la-upload"></i>
                                <span class="category-name">
                                    {{ translate('Product Bulk Upload')}}
                                </span>
                            </a>
                        </li>


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

                <li style="list-style-type: none">
                    <a href="{{ route('reviews.seller') }}" class="{{ areActiveRoutesHome(['reviews.seller'])}}">
                        <i class="la la-star-o"></i>
                        <span class="category-name">
                            {{ translate('Product Reviews')}}@if($review_count > 0)<span class="ml-2" style="color:green"><strong>({{ $review_count }} {{  translate('New') }})</strong></span>@endif
                        </span>
                    </a>
                </li>

                    </div>
                </ul>
                <!-- Listing end -->

                  
                <!-- Order start -->
                @php
                    $orders = DB::table('orders')
                                ->orderBy('code', 'desc')
                                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                                ->where('order_details.seller_id', Auth::user()->id)
                                ->where('orders.viewed', 0)
                                ->where('order_details.payment_status', 'paid')
                                ->select('orders.id')
                                ->distinct()
                                ->count();
                @endphp
                
                <li>
                    <a href="{{ route('orders.index') }}" class="{{ areActiveRoutesHome(['orders.index'])}}">
                        <i class="la la-file-text"></i>
                        <span class="category-name">
                            {{ translate('Orders')}} @if($orders > 0)<span class="ml-2" style="color:green"><strong>({{ $orders }} {{  translate('New') }})</strong></span></span>@endif
                        </span>
                    </a>
                </li>
                <!-- Order end -->

                <!-- Refund start -->
                <li>
                    <a data-toggle="collapse" class="{{ areActiveRoutesHome(['customer_refund_request', 'vendor_refund_request'])}}" href="#refund" role="button" aria-expanded="false" aria-controls="collapsesetup">
                        <i class="fa fa-credit-card"></i>
                        <span class="category-name">
                            {{ translate('Refund')}}
                            <i class="dropdown-toggle right"></i>
                        </span>
                    </a>
                </li>

                <ul>
                    <div class="collapse" id="refund">
                    @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                    <li style="list-style-type: none">
                        <a href="{{ route('customer_refund_request') }}" class="{{ areActiveRoutesHome(['customer_refund_request'])}}">
                            <i class="fa fa-paper-plane"></i>
                            <span class="category-name">
                                {{ translate('Sent Request')}}
                            </span>
                        </a>
                    </li>
                    <li style="list-style-type: none">
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
                <!-- Refund end -->

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

                <!-- Conversation start -->
              
                @if (\App\BusinessSetting::where('type', 'conversation_system')->first()->value == 1)
                    @php
                        $conversation_sent = \App\Conversation::where('sender_id', Auth::user()->id)->where('sender_viewed', 0)->get();
                        $conversation_recieved = \App\Conversation::where('receiver_id', Auth::user()->id)->where('receiver_viewed', 0)->get();
                    @endphp
                    <li>
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
                <!-- Conversation ends -->

               
                <!--<li>
                    <a href="{{ route('profile') }}" class="{{ areActiveRoutesHome(['profile'])}}">
                        <i class="la la-user"></i>
                        <span class="category-name">
                            {{ translate('Manage Profile')}}
                        </span>
                    </a>
                </li> -->
                <!-- <li>
                    <a href="{{ route('withdraw_requests.index') }}" class="{{ areActiveRoutesHome(['withdraw_requests.index'])}}">
                        <i class="la la-money"></i>
                        <span class="category-name">
                            {{ translate('Money Withdraw')}}
                        </span>
                    </a>
                </li> -->

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



                <!-- My Shopping start -->
                <li>
                    <a data-toggle="collapse" class="{{ areActiveRoutesHome(['wishlists.index', 'digital_purchase_history.index', 'payments.index', 'purchase_history.index'])}}" href="#myshopping" role="button" aria-expanded="false" aria-controls="collapsesetup">
                        <i class="fa fa-briefcase"></i>
                        <span class="category-name">
                            {{ translate('My Shopping')}}
                            <i class="dropdown-toggle right"></i>
                        </span>
                    </a>
                </li>
                <ul>
                <div class="collapse" id="myshopping">
                
                <li style="list-style-type: none;">
                    <a href="{{ route('wishlists.index') }}" class="{{ areActiveRoutesHome(['wishlists.index'])}}">
                        <i class="la la-heart-o"></i>
                        <span class="category-name">
                            {{ translate('Wishlist')}}
                        </span>
                    </a>
                </li>
              

                <li style="list-style-type: none;">
                    <a href="{{ route('digital_purchase_history.index') }}" class="{{ areActiveRoutesHome(['digital_purchase_history.index'])}}">
                        <i class="la la-download"></i>
                        <span class="category-name">
                            {{ translate('Downloads')}}
                        </span>
                    </a>
                </li>

                <li style="list-style-type: none;">
                    <a href="{{ route('payments.index') }}" class="{{ areActiveRoutesHome(['payments.index'])}}">
                        <i class="la la-cc-mastercard"></i>
                        <span class="category-name">
                            {{ translate('Payment History')}}
                        </span>
                    </a>
                </li>

                <li style="list-style-type: none;">
                    <a href="{{ route('purchase_history.index') }}" class="{{ areActiveRoutesHome(['purchase_history.index'])}}">
                        <i class="la la-file-text"></i>
                        <span class="category-name">
                            {{ translate('Purchase History')}} @if($delivery_viewed > 0 || $payment_status_viewed > 0)<span class="ml-2" style="color:green"><strong>({{  translate(' New Notifications') }})</strong></span>@endif
                        </span>
                    </a>
                </li>

                </div>  
                </ul>
                <!-- My Shopping end -->


                
                <!-- Business setup start -->
                <li>
                    <a data-toggle="collapse" class="{{ areActiveRoutesHome(['get.payment.setup', 'get.domain.setup', 'get.shipping.setup', 'get_seller_type', 'kyc', 'profile' ])}}" href="#collapsesetup" role="button" aria-expanded="false" aria-controls="collapsesetup">
                        <i class="fa fa-cogs"></i>
                        <span class="category-name">
                            {{ translate('Settings')}}
                            <i class="dropdown-toggle right"></i>
                        </span>
                    </a>
                </li>
                <ul>
                <div class="collapse" id="collapsesetup">
                <li style="list-style-type: none">
                    <a href="{{route('get.payment.setup')}}" class="{{ areActiveRoutesHome(['get.payment.setup'])}}">
                        <i class="fa fa-credit-card"></i>
                        <span class="category-name">
                            {{ translate('Payment Setup')}}
                        </span>
                    </a>
                </li>

                <li style="list-style-type: none">
                    <a href="{{route('get.domain.setup')}}" class="{{ areActiveRoutesHome(['get.domain.setup'])}}">
                        <i class="fa fa-globe"></i>
                        <span class="category-name">
                            {{ translate('Domain Setup')}}
                        </span>
                    </a>
                </li>
                @if($shop->seller_type == 'goods' || $shop->seller_type == 'both')
                <li style="list-style-type: none">
                    <a href="{{route('get.shipping.setup')}}" class="{{ areActiveRoutesHome(['get.shipping.setup'])}}"  >
                        <i class="fa fa-shopping-cart"></i>
                        <span class="category-name">
                            {{ translate('Shipping Setup')}}
                        </span>
                    </a>
                </li>
                @endif


                <li style="list-style-type: none">
                    <a href="{{route('get_seller_type')}}" class="{{ areActiveRoutesHome(['get_seller_type'])}}"  >
                        <i class="fa fa-bullseye"></i>
                        <span class="category-name">
                            {{ translate('Seller Type Settings')}}
                        </span>
                    </a>
                </li>

                <li style="list-style-type: none">
                    <a href="{{route('kyc')}}" class="{{ areActiveRoutesHome(['kyc'])}}"  >
                        <i class="fa fa-check"></i>
                        <span class="category-name">
                            {{ translate('Document Verification')}}
                        </span>
                    </a>
                </li>

                <li style="list-style-type: none">
                    <a href="{{route('profile')}}" class="{{ areActiveRoutesHome(['profile'])}}"  >
                        <i class="fa fa-user"></i>
                        <span class="category-name">
                            {{ translate('Manage Profile')}}
                        </span>
                    </a>
                </li>
        
                </div>  
                </ul>
                <!-- Business setup end -->


                <!-- Business Stationary start -->
                <li>
                    <a data-toggle="collapse" class="{{ areActiveRoutesHome(['visiting_card', 'letterhead'])}}" href="#stationary" role="button" aria-expanded="false" aria-controls="collapsesetup">
                        <i class="fa fa-suitcase"></i>
                        <span class="category-name">
                            {{ translate('Business Stationary')}}
                            <i class="dropdown-toggle right"></i>
                        </span>
                    </a>
                </li>
                <ul>
                <div class="collapse" id="stationary">
                <li style="list-style-type: none">
                    <a href="{{ route('visiting_card') }}" class="{{ areActiveRoutesHome(['visiting_card'])}}">
                        <i class="fa fa-address-card"></i>
                        <span class="category-name">
                            {{ translate('Visiting Card')}}
                        </span>
                    </a>
                </li>

                <li style="list-style-type: none">
                    <a href="{{ route('letterhead') }}" class="{{ areActiveRoutesHome(['letterhead'])}}">
                        <i class="fa fa-bookmark"></i>
                        <span class="category-name">
                            {{ translate('Letterhead')}}
                        </span>
                    </a>
                </li>
                
                </div>  
                </ul>
                <!-- Business Stationary end -->
                
                @php
                    $support_ticket = DB::table('tickets')
                                ->where('client_viewed', 0)
                                ->where('user_id', Auth::user()->id)
                                ->count();
                @endphp
                <li>
                    <a href="{{ route('support_ticket.index') }}" class="{{ areActiveRoutesHome(['support_ticket.index', 'support_ticket.show'])}}">
                        <i class="la la-support"></i>
                        <span class="category-name">
                            {{ translate('Support Ticket')}} @if($support_ticket > 0)<span class="ml-2" style="color:green"><strong>({{ $support_ticket }} {{  translate('New') }})</strong></span></span>@endif
                        </span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-widget-title py-3">
            <span>{{ translate('Sold Amount')}}</span>
        </div>
        <div class="widget-balance pb-3 pt-1">
            <div class="text-center">
                <div class="heading-4 strong-700 mb-4">
                    @php
                        $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->where('created_at', '>=', date('Y-m-d', strtotime("-30 days")))->get();
                        $total = 0;
                        foreach ($orderDetails as $key => $orderDetail) {
                            if($orderDetail->order->payment_status == 'paid'){
                                $total += $orderDetail->price;
                            }
                        }
                    @endphp
                    <small class="d-block text-sm alpha-5 mb-2">{{ translate('Your sold amount (current month)')}}</small>
                    <span class="p-2 bg-base-1 rounded">{{ single_price($total) }}</span>
                </div>
                <table class="text-left mb-0 table w-75 m-auto">
                    <tr>
                        @php
                            $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->get();
                            $total = 0;
                            foreach ($orderDetails as $key => $orderDetail) {
                                if($orderDetail->order->payment_status == 'paid'){
                                    $total += $orderDetail->price;
                                }
                            }
                        @endphp
                        <td class="p-1 text-sm">
                            {{ translate('Total Sold')}}:
                        </td>
                        <td class="p-1">
                            {{ single_price($total) }}
                        </td>
                    </tr>
                    <tr>
                        @php
                            $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->where('created_at', '>=', date('Y-m-d', strtotime("-60 days")))->where('created_at', '<=', date('Y-m-d', strtotime("-30 days")))->get();
                            $total = 0;
                            foreach ($orderDetails as $key => $orderDetail) {
                                if($orderDetail->order->payment_status == 'paid'){
                                    $total += $orderDetail->price;
                                }
                            }
                        @endphp
                        <td class="p-1 text-sm">
                            {{ translate('Last Month Sold')}}:
                        </td>
                        <td class="p-1">
                            {{ single_price($total) }}
                        </td>
                    </tr>
                </table>
            </div>
            <table>

            </table>
        </div>
    </div>
</div>