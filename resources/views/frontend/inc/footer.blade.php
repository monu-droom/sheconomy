
<!--<section class="slice-sm footer-top-bar bg-white">
    <div class="container sct-inner">
        <div class="row no-gutters">
            <div class="col-lg-3 col-md-6">
                <div class="footer-top-box text-center">
                    <a href="{{ route('sellerpolicy') }}">
                        <i class="la la-file-text"></i>
                        <h4 class="heading-5">{{ translate('Seller Policy') }}</h4>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-top-box text-center">
                    <a href="{{ route('returnpolicy') }}">
                        <i class="la la-mail-reply"></i>
                        <h4 class="heading-5">{{ translate('Return Policy') }}</h4>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-top-box text-center">
                    <a href="{{ route('supportpolicy') }}">
                        <i class="la la-support"></i>
                        <h4 class="heading-5">{{ translate('Support Policy') }}</h4>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-top-box text-center">
                    <a href="{{ route('profile') }}">
                        <i class="la la-dashboard"></i>
                        <h4 class="heading-5">{{ translate('My Profile') }}</h4>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>-->


<!-- FOOTER -->
<footer id="footer" class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                @php
                    $generalsetting = \App\GeneralSetting::first();
                @endphp
                <div class="col-lg-9 col-xl-8">
                    <div class="row">
                        <!-- First Row start -->
                        <div class="col-lg-3 col-xl-3 shfooter">
                           <div class="col text-center text-md-left">
                            <h4 class="d-none d-md-block heading heading-xs strong-600 text-uppercase mb-2">
                                {{ translate('ABOUT') }}
                            </h4>
                            <h4 class="d-md-none title heading heading-xs strong-600 text-uppercase mb-2" data-target="#aboutus" data-toggle="collapse">
                            <div class="float-right">
                                <i class="fa fa-angle-down"></i>
                                <i class="fa fa-angle-up"></i>
                            </div>
                                {{ translate('ABOUT') }}
                            </h4>
                                <ul id="aboutus" class="footer-links collapse">
                                    <li><a href="/about-founder" title="">{{ translate('About Founder') }}</a></li>
                                    <li><a href="/contact-us" title="">{{ translate('Contact Us') }}</a></li>
                                    <li><a href="/about-us" title="">{{ translate('About Us') }}</a></li>
                                    <li><a href="/careers" title="">{{ translate('Careers') }}</a></li>
                                    <li><a href="/sheconomy-stories" title="">{{ translate('SHEconomy Stories') }}</a></li>
                                    <li><a href="/press" title="">{{ translate('Press') }}</a></li>
                                    <li><a href="/blog" title="">{{ translate('Blog') }}</a></li>
                                    <!-- <li><a href="/press" title="">{{ translate('Press') }}</a></li> -->
                                </ul>
                            </div>
                        </div>
                        <!-- First row end -->
                        <!-- Second row start -->
                        <div class="col-lg-3 col-md-3 shfooter">
                           <div class="col text-center text-md-left">
                           <h4 class="d-none d-md-block heading heading-xs strong-600 text-uppercase mb-2">
                                {{ translate('HELP') }}
                            </h4>
                            <h4 class="d-md-none title heading heading-xs strong-600 text-uppercase mb-2" data-target="#helps" data-toggle="collapse">
                            <div class="float-right">
                                <i class="fa fa-angle-down"></i>
                                <i class="fa fa-angle-up"></i>
                            </div>
                                {{ translate('HELP') }}
                            </h4>
                                <ul id="helps" class="footer-links collapse">
                                    <li><a href="/payments" title="">{{ translate('Payments') }}</a></li>
                                    <li><a href="/shipping" title="">{{ translate('Shipping') }}</a></li>
                                    <li><a href="/cancellation-returns" title="">{{ translate('Cancellation & Returns') }}</a></li>
                                    <li><a href="/faqs" title="">{{ translate('FAQ\'s') }}</a></li>
                                    <li><a href="/report-infringement" title="">{{ translate('Report Infringement') }}</a></li>
                                </ul>
                            </div> 
                        </div>
                        <!-- Second row end -->
                        <!-- Third row start -->
                        <div class="col-lg-3 col-md-3 shfooter">
                            <div class="col text-center text-md-left">
                            <h4 class="d-none d-md-block heading heading-xs strong-600 text-uppercase mb-2">
                                {{ translate('POLICY') }}
                            </h4>
                            <h4 class="d-md-none title heading heading-xs strong-600 text-uppercase mb-2" data-target="#policies" data-toggle="collapse">
                            <div class="float-right">
                                <i class="fa fa-angle-down"></i>
                                <i class="fa fa-angle-up"></i>
                            </div>
                                {{ translate('POLICY') }}
                            </h4>
                                <ul id="policies" class="footer-links collapse">
                                    <!-- <li><a href="{{ route('returnpolicy') }}" title="">{{ translate('Returns Policy') }}</a></li> -->
                                    <li><a href="{{ route('terms') }}" title="">{{ translate('Terms of Use') }}</a></li>
                                    <li><a href="/security" title="">{{ translate('Security') }}</a></li>
                                    <li><a href="{{ route('privacypolicy') }}" title="">{{ translate('Privacy') }}</a></li>
                                    <!-- <li><a href="/Sitemap" title="">{{ translate('Sitemap') }}</a></li> -->
                                    <li><a href="/prohibited_list" title="">{{ translate('Prohibited List') }}</a></li>
                                    <li><a href="/code-of-conduct" title="">{{ translate('Code of Conduct') }}</a></li>
                                    <!-- <li><a href="/eprcompliance" title="">{{ translate('EPR Compliance') }}</a></li> -->
                                </ul>
                            </div>
                        </div>
                        <!-- Third row end -->
                        <!-- Forth row start -->
                        <div class="col-md-3 col-lg-3 shfooter">
                            <div class="col text-center text-md-left">
                            <h4 class="d-none d-md-block heading heading-xs strong-600 text-uppercase mb-2">
                                {{ translate('My Account') }}
                            </h4>
                            <h4 class="d-md-none title heading heading-xs strong-600 text-uppercase mb-2" data-target="#myaccounts" data-toggle="collapse">
                            <div class="float-right">
                                <i class="fa fa-angle-down"></i>
                                <i class="fa fa-angle-up"></i>
                            </div>
                                {{ translate('My Account') }}
                            </h4>
                               <ul id="myaccounts" class="footer-links collapse">
                                    @if (Auth::check())
                                        <li>
                                            <a href="{{ route('logout') }}" title="Logout">
                                                {{ translate('Logout') }}
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ route('user.login') }}" title="Login">
                                                {{ translate('Login') }}
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="{{ route('purchase_history.index') }}" title="Order History">
                                            {{ translate('Order History') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('wishlists.index') }}" title="My Wishlist">
                                            {{ translate('My Wishlist') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('orders.track') }}" title="Track Order">
                                            {{ translate('Track Order') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @if (\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                                <div class="col text-center text-md-left">
                                    <div class="mt-4">
                                        <h4 class="heading heading-xs strong-600 text-uppercase mb-2">
                                            {{ translate('Be a Seller') }}
                                        </h4>
                                        <a href="{{ route('shops.create') }}" class="btn btn-base-1 btn-icon-left">
                                            {{ translate('Apply Now') }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- Forth row end -->
                    </div> 
                </div>
                <div class="col-lg-3 col-xl-4">
                  <div class="col text-center text-md-left">
                                <h4 class="heading heading-xs strong-600 text-uppercase mb-2">
                                    {{ translate('Registered Office Address:') }}
                                </h4>
                                <ul class="footer-links contact-widget">
                                    <li>
                                       <span class="d-block opacity-5">{{ translate('Address') }}:</span>
                                       <span class="d-block">{{ $generalsetting->address }}</span>
                                    </li>
                                    <li>
                                       <span class="d-block">{{translate('Phone')}}: {{ $generalsetting->phone }}</span>
                                    </li>
                                    <li>
                                       <span class="d-block">{{translate('Email')}}: <a href="mailto:{{ $generalsetting->email }}">{{ $generalsetting->email  }}</a></span>
                                       
                                    </li>
                                    <li>
                                       <span class="d-block">{{translate('CIN')}}: U74999DL2020PTC365879</span>
                                    </li>
                                </ul>
                    </div>  
                </div>
                
            </div>
        </div>
    </div>

    <div class="footer-bottom py-3 sct-color-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="copyright text-center text-md-left">
                        <ul class="copy-links no-margin">
                            <li>
                                © {{ date('Y') }} {{ $generalsetting->site_name }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="text-center my-3 my-md-0 social-nav model-2">
                        @if ($generalsetting->facebook != null)
                            <li>
                                <a href="{{ $generalsetting->facebook }}" class="facebook" target="_blank" data-toggle="tooltip" data-original-title="Facebook">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                        @endif
                        @if ($generalsetting->instagram != null)
                            <li>
                                <a href="{{ $generalsetting->instagram }}" class="instagram" target="_blank" data-toggle="tooltip" data-original-title="Instagram">
                                    <i class="fa fa-instagram"></i>
                                </a>
                            </li>
                        @endif
                        @if ($generalsetting->twitter != null)
                            <li>
                                <a href="{{ $generalsetting->twitter }}" class="twitter" target="_blank" data-toggle="tooltip" data-original-title="Twitter">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                        @endif
                        @if ($generalsetting->youtube != null)
                            <li>
                                <a href="{{ $generalsetting->youtube }}" class="youtube" target="_blank" data-toggle="tooltip" data-original-title="Youtube">
                                    <i class="fa fa-youtube"></i>
                                </a>
                            </li>
                        @endif
                        @if ($generalsetting->google_plus != null)
                            <li>
                                <a href="{{ $generalsetting->google_plus }}" class="community" target="_blank" data-toggle="tooltip" data-original-title="Community">
                                    <img src="{{ asset('public/community-logo.png')}}" style="width:43px; height: 42px;" alt="" class="img-responsive">
                                </a>
                            </li> 
                        @endif
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="text-center text-md-right">
                        <ul class="inline-links">
                            @if (\App\BusinessSetting::where('type', 'paypal_payment')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="paypal" src="{{ my_asset('frontend/images/icons/cards/paypal.png')}}" height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'stripe_payment')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="stripe" src="{{ my_asset('frontend/images/icons/cards/stripe.png')}}" height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'sslcommerz_payment')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="sslcommerz" src="{{ my_asset('frontend/images/icons/cards/sslcommerz-foo.png')}}" height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'instamojo_payment')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="instamojo" src="{{ my_asset('frontend/images/icons/cards/instamojo.png')}}" height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'razorpay')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="razorpay" src="{{ my_asset('frontend/images/icons/cards/rozarpay.png')}}" height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'voguepay')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="voguepay" src="{{ my_asset('frontend/images/icons/cards/voguepay.png')}}" height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'paystack')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="paystack" src="{{ my_asset('frontend/images/icons/cards/paystack.png')}}" height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'payhere')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="payhere" src="{{ my_asset('frontend/images/icons/cards/payhere.png')}}" height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'cash_payment')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="cash on delivery" src="{{ my_asset('frontend/images/icons/cards/cod.png')}}" height="30">
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>