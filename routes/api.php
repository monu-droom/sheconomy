<?php

Route::prefix('v1/auth')->group(function () {
    Route::post('login', 'Api\AuthController@login');
    Route::post('signup', 'Api\AuthController@signup');
    Route::post('social-login', 'Api\AuthController@socialLogin');
    Route::post('password/create', 'Api\PasswordResetController@create');
    Route::middleware('auth:api')->group(function () {
        Route::get('logout', 'Api\AuthController@logout');
        Route::get('user', 'Api\AuthController@user');
    });
});

Route::prefix('v1')->group(function () {
    Route::apiResource('banners', 'Api\BannerController')->only('index');

    Route::get('brands/top', 'Api\BrandController@top');
    Route::apiResource('brands', 'Api\BrandController')->only('index');

    Route::apiResource('business-settings', 'Api\BusinessSettingController')->only('index');

    Route::get('categories/featured', 'Api\CategoryController@featured');
    Route::get('categories/home', 'Api\CategoryController@home');
    Route::apiResource('categories', 'Api\CategoryController')->only('index');
    Route::get('categories/top', 'Api\CategoryController@top');
    Route::get('sub-categories/{id}', 'Api\SubCategoryController@index')->name('subCategories.index');

    Route::apiResource('colors', 'Api\ColorController')->only('index');

    Route::apiResource('currencies', 'Api\CurrencyController')->only('index');

    Route::apiResource('customers', 'Api\CustomerController')->only('show');

    Route::apiResource('general-settings', 'Api\GeneralSettingController')->only('index');

    Route::apiResource('home-categories', 'Api\HomeCategoryController')->only('index');

    Route::get('purchase-history/{id}', 'Api\PurchaseHistoryController@index');
    Route::get('purchase-history-details/{id}', 'Api\PurchaseHistoryDetailController@index')->name('purchaseHistory.details');

    Route::get('products/admin', 'Api\ProductController@admin');
    Route::get('products/seller', 'Api\ProductController@seller');
    Route::get('products/category/{id}', 'Api\ProductController@category')->name('api.products.category');
    Route::get('products/sub-category/{id}', 'Api\ProductController@subCategory')->name('products.subCategory');
    Route::get('products/sub-sub-category/{id}', 'Api\ProductController@subSubCategory')->name('products.subSubCategory');
    Route::get('products/brand/{id}', 'Api\ProductController@brand')->name('api.products.brand');
    Route::get('products/todays-deal', 'Api\ProductController@todaysDeal');
    Route::get('products/flash-deal', 'Api\ProductController@flashDeal');
    Route::get('products/featured', 'Api\ProductController@featured');
    Route::get('products/featuredServices', 'Api\ProductController@featuredServices');
    Route::get('products/best-seller', 'Api\ProductController@bestSeller');
    Route::get('products/best-selling', 'Api\ProductController@bestSelling');
    Route::get('products/related/{id}', 'Api\ProductController@related')->name('products.related');
    Route::get('products/top-from-seller/{id}', 'Api\ProductController@topFromSeller')->name('products.topFromSeller');
    Route::get('products/search', 'Api\ProductController@search');
    Route::post('products/variant/price', 'Api\ProductController@variantPrice');
    Route::get('products/home', 'Api\ProductController@home');
    Route::apiResource('products', 'Api\ProductController')->except(['store', 'update', 'destroy']);

    Route::get('carts/{id}', 'Api\CartController@index');
    Route::post('carts/add', 'Api\CartController@add');
    Route::post('carts/change-quantity', 'Api\CartController@changeQuantity');
    Route::apiResource('carts', 'Api\CartController')->only('destroy');
    Route::post('carts/summary', 'Api\CartController@cartSummary');

    Route::get('reviews/product/{id}', 'Api\ReviewController@index')->name('api.reviews.index');
    Route::post('reviews/save', 'Api\ReviewController@reviewSave')->name('reviews.save');

    Route::get('shop/user/{id}', 'Api\ShopController@shopOfUser');
    Route::get('shops/details/{id}', 'Api\ShopController@info')->name('shops.info');
    Route::get('shops/products/all/{id}', 'Api\ShopController@allProducts')->name('shops.allProducts');
    Route::get('shops/products/top/{id}', 'Api\ShopController@topSellingProducts')->name('shops.topSellingProducts');
    Route::get('shops/products/featured/{id}', 'Api\ShopController@featuredProducts')->name('shops.featuredProducts');
    Route::get('shops/products/new/{id}', 'Api\ShopController@newProducts')->name('shops.newProducts');
    Route::get('shops/brands/{id}', 'Api\ShopController@brands')->name('shops.brands');
    Route::apiResource('shops', 'Api\ShopController')->only('index');

    Route::apiResource('sliders', 'Api\SliderController')->only('index');

    Route::apiResource('wishlists', 'Api\WishlistController')->except(['index', 'update', 'show', 'destroy'])->middleware('auth:api');
    Route::get('wishlists/{id}', 'Api\WishlistController@index');
    Route::post('wishlists_save', 'Api\WishlistController@store');
    Route::get('wishlists_delete/{id}', 'Api\WishlistController@destroy');
    Route::post('wishlists/check-product', 'Api\WishlistController@isProductInWishlist');

    Route::apiResource('settings', 'Api\SettingsController')->only('index');

    Route::get('policies/seller', 'Api\PolicyController@sellerPolicy')->name('policies.seller');
    Route::get('policies/support', 'Api\PolicyController@supportPolicy')->name('policies.support');
    Route::get('policies/return', 'Api\PolicyController@returnPolicy')->name('policies.return');

    Route::get('user/info/{id}', 'Api\UserController@info')->middleware('auth:api');
    Route::post('user/info/update', 'Api\UserController@updateName');
    Route::get('user/shipping/address/{id}', 'Api\AddressController@addresses')->middleware('auth:api');
    Route::post('user/shipping/create', 'Api\AddressController@createShippingAddress')->middleware('auth:api');
    Route::get('user/shipping/delete/{id}', 'Api\AddressController@deleteShippingAddress')->middleware('auth:api');

    Route::post('coupon/apply', 'Api\CouponController@apply');

    Route::any('razorpay/pay-with-razorpay', 'Api\RazorpayController@payWithRazorpay')->name('api.razorpay.payment');
    Route::any('razorpay/payment', 'Api\RazorpayController@payment')->name('api.razorpay.payment');
    Route::post('razorpay/success', 'Api\RazorpayController@success')->name('api.razorpay.success');

    Route::any('stripe', 'Api\StripeController@stripe');
    Route::any('/stripe/create-checkout-session', 'Api\StripeController@create_checkout_session')->name('api.stripe.get_token');
    Route::any('/stripe/payment/callback', 'Api\StripeController@callback')->name('api.stripe.callback');
    Route::any('/stripe/success', 'Api\StripeController@success')->name('api.stripe.success');
    Route::any('/stripe/cancel', 'Api\StripeController@cancel')->name('api.stripe.cancel');

    Route::post('payments/pay/paypal', 'Api\PaypalController@processPayment');

    Route::post('payments/pay/wallet', 'Api\WalletController@processPayment');
    Route::post('payments/pay/cod', 'Api\PaymentController@cashOnDelivery');
    Route::post('payments/modes', 'Api\PaymentController@paymentMode');

    Route::post('order/store', 'Api\OrderController@store');

    Route::get('wallet/balance/{id}', 'Api\WalletController@balance');
    Route::get('wallet/history/{id}', 'Api\WalletController@walletRechargeHistory');
    //SellerPaymentSetting
    Route::get('seller-payment', 'Api\SellerPaymentController@index');
    Route::get('seller-payment/{id}', 'Api\SellerPaymentController@show');
    //Seller_Shipping_details
    Route::get('seller-shipping', 'Api\SellerShippingController@index');
    Route::get('seller-shipping/{id}', 'Api\SellerShippingController@show');
    //change currency
    Route::get('change-currency/{country}', 'Api\CountryCurrController@index');
    //County Api
    Route::get('country', 'Api\CountryController@index');  
    //Registration Steps Api
    Route::post('seller/signup', 'Api\StepsController@beSeller');
    Route::post('seller/verify_otp', 'Api\StepsController@verifyOtp');
    Route::get('seller/basic_info', 'Api\StepsController@basicInfo');
    Route::post('seller/basic_info', 'Api\StepsController@updateBasicInfo');
    Route::post('seller/home_page_setup', 'Api\StepsController@postHomeInfo');
    Route::post('seller/about_info', 'Api\StepsController@postAboutInfo');
    Route::post('seller/policy', 'Api\StepsController@postPolicyInfo');
    Route::get('seller/account_type', 'Api\StepsController@getAccountType');
    Route::post('seller/kyc_india', 'Api\StepsController@sellerKycIndia');
    Route::post('seller/kyc_non_india', 'Api\StepsController@sellerKycNonIndia');
    Route::post('seller/country', 'Api\StepsController@getCountry');
    Route::post('seller/paypal_setup', 'Api\StepsController@postPaypalSetup');
    Route::post('seller/razorpay_setup', 'Api\StepsController@postRazorpaySetup');
    Route::post('seller/stripe_setup', 'Api\StepsController@postStripeSetup');
    Route::post('seller/instamozo_setup', 'Api\StepsController@postInstamojoSetup');
    Route::post('seller/shipping_setup', 'Api\StepsController@postShippingSetup');
    Route::post('seller/domain_setup', 'Api\StepsController@postDomainSetup');
    Route::post('seller/product_upload', 'Api\StepsController@postProduct');
    Route::post('seller/service_upload', 'Api\StepsController@postServices');
    //Conversation
    Route::post('chat_with_seller', 'Api\ConversationController@store');
});

Route::fallback(function() {
    return response()->json([
        'data' => [],
        'success' => false,
        'status' => 404,
        'message' => 'Invalid Route'
    ]);
});
