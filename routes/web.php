<?php
use Illuminate\Support\Facades\Route; 
use App\Mail\RegisterMailManager;
use App\Shop;
use Stevebauman\Location\Facades\Location;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//---For GeoLocation Of A User----
$clientIp = \Request::getClientIp();
$position = Location::get($clientIp);	
if(!empty($position) && strtolower($position->countryName) == 'india'){
	session()->put('currency_code', 'Rupee');
	session()->put('location', $position);
}else{
	session()->put('currency_code', 'USD');
	session()->put('location', $position);
}
// use App\Mail\SupportMailManager;
//Dynamic subdomain
 if(request()->getHost()!='www.sheconomy.in'){
     Route::group(['domain' => '{domain}.sheconomy.in'], function () {
         Route::get('/', 'HomeController@shop')->name('shop.visit');
     });
 }else{
	Route::get('/shops/visit/{domain}', 'HomeController@shop')->name('shop.visit');		 
 } 
//demo
Route::get('/demo/cron_1', 'DemoController@cron_1');
Route::get('/demo/cron_2', 'DemoController@cron_2');

// For steps when user register first.
Route::get('reg_1/basic-info', 'Steps@getBasicInfo')->name('reg_1');
Route::resource('steps','Steps');
Route::get('reg_1/home-info', 'Steps@getHomeInfo')->name('steps.home-info');
Route::post('reg_1/home-info/{id}', 'Steps@postHomeInfo')->name('steps.home_update');
Route::get('reg_1/about-info', 'Steps@getAboutInfo')->name('steps.about-info');
Route::post('reg_1/about-info/{id}', 'Steps@postAboutInfo')->name('steps.about-update');
Route::get('reg_1/policy-info', 'Steps@getPolicyInfo')->name('steps.policy');
Route::post('reg_1/policy-info/{id}', 'Steps@postPolicyInfo')->name('steps.policy_info');
Route::get('reg_2/kyc', 'Steps@getKyc')->name('steps.kyc');
Route::post('/steps_seller_kyc', 'Steps@sellerKyc')->name('steps.seller.kyc');
Route::post('/steps_seller_kyc/non_india', 'Steps@sellerKycNonIndia')->name('steps.seller.kyc.non.india');
Route::get('reg_3/payment-info', 'Steps@getPaymentInfo')->name('steps.payments');
Route::post('reg_3/paypal', 'Steps@postPaymentInfo')->name('steps.paypal');
Route::post('reg_3/razorpay', 'Steps@postRazorpaySetup')->name('steps.razorpay');
Route::post('reg_3/stripe', 'Steps@postStripeSetup')->name('steps.stripe');
Route::post('reg_3/instamojo', 'Steps@postInstamojoSetup')->name('steps.instamojo');
Route::get('reg_4/shipping', 'Steps@getShippingSetup')->name('steps.shipping');
Route::post('reg_4/shipping-setup', 'Steps@postShippingSetup')->name('steps.shipping.setup');
Route::post('reg_4/update-shipping', 'Steps@postShippingUpdate')->name('steps.update_shipping');
Route::get('reg_5/domain', 'Steps@stepGetDomainSetup')->name('steps.domain');
Route::post('reg_5/domain-setup', 'Steps@stepPostDomainSetup')->name('steps.domain-setup');
Route::get('reg_6/product', 'Steps@getProduct')->name('steps.product');
Route::get('reg_6/service', 'Steps@getServices')->name('steps.service');
Route::post('reg_6/add-product', 'Steps@postProduct')->name('steps.add-product');
Route::post('reg_6/add-service', 'Steps@postServices')->name('steps.add-service');
// End of steps here.

//Visiting Cards
Route::get('/visiting-cards', 'HomeController@visitingCards')->name('visiting_card');
Route::post('/visiting-cards-save', 'HomeController@saveVisitingCards')->name('saveImage');
Route::get('/letter-head', 'HomeController@letterHead')->name('letterhead');
Route::post('/letter-head-save', 'HomeController@saveLetterHead')->name('saveLetterHead');

// For policy get and post 
Route::get('/policies', 'HomeController@getPolicy')->name('policies');
Route::post('/policies/{id}', 'HomeController@addPolicy')->name('seller.policies');

Route::get('android/api', 'HomeController@androidApi')->name('android.api');

Auth::routes(['verify' => true]);
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
Route::get('/verification-confirmation/{code}', 'Auth\VerificationController@verification_confirmation')->name('email.verification.confirmation');

// $user = Auth::loginUsingId(3);
// // dd($user);
 
// Route::get('testemail', function () use ($user)  {
//     Mail::to('adnan.ahmad@sheconomy.in')->send(new RegisterMailManager($user));
// });

Route::post('/language', 'LanguageController@changeLanguage')->name('language.change');
Route::post('/currency', 'CurrencyController@changeCurrency')->name('currency.change');

Route::get('/social-login/redirect/{provider}', 'Auth\LoginController@redirectToProvider')->name('social.login');
Route::get('/social-login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('social.callback');
Route::get('/users/login', 'HomeController@login')->name('user.login');
Route::get('/users/registration', 'HomeController@registration')->name('user.registration');
//Route::post('/users/login', 'HomeController@user_login')->name('user.login.submit');
Route::post('/users/login/cart', 'HomeController@cart_login')->name('cart.login.submit');

Route::post('/subcategories/get_subcategories_by_category', 'SubCategoryController@get_subcategories_by_category')->name('subcategories.get_subcategories_by_category');
Route::post('/subsubcategories/get_subsubcategories_by_subcategory', 'SubSubCategoryController@get_subsubcategories_by_subcategory')->name('subsubcategories.get_subsubcategories_by_subcategory');
Route::post('/subsubcategories/get_brands_by_subsubcategory', 'SubSubCategoryController@get_brands_by_subsubcategory')->name('subsubcategories.get_brands_by_subsubcategory');
Route::post('/subsubcategories/get_attributes_by_subsubcategory', 'SubSubCategoryController@get_attributes_by_subsubcategory')->name('subsubcategories.get_attributes_by_subsubcategory');

//Home Page
Route::get('/', 'HomeController@index')->name('home');
Route::post('/home/section/featured', 'HomeController@load_featured_section')->name('home.section.featured');
Route::post('/home/section/best_selling', 'HomeController@load_best_selling_section')->name('home.section.best_selling');
Route::post('/home/section/home_categories', 'HomeController@load_home_categories_section')->name('home.section.home_categories');
Route::post('/home/section/best_sellers', 'HomeController@load_best_sellers_section')->name('home.section.best_sellers');
//category dropdown menu ajax call
Route::post('/category/nav-element-list', 'HomeController@get_category_items')->name('category.elements');

//Flash Deal Details Page
Route::get('/flash-deal/{slug}', 'HomeController@flash_deal_details')->name('flash-deal-details');

Route::get('/sitemap.xml', function(){
	return base_path('sitemap.xml');
});
 

Route::get('/customer-products', 'CustomerProductController@customer_products_listing')->name('customer.products');
Route::get('/customer-products?subsubcategory={subsubcategory_slug}', 'CustomerProductController@search')->name('customer_products.subsubcategory');
Route::get('/customer-products?subcategory={subcategory_slug}', 'CustomerProductController@search')->name('customer_products.subcategory');
Route::get('/customer-products?category={category_slug}', 'CustomerProductController@search')->name('customer_products.category');
Route::get('/customer-products?city={city_id}', 'CustomerProductController@search')->name('customer_products.city');
Route::get('/customer-products?q={search}', 'CustomerProductController@search')->name('customer_products.search');
Route::get('/customer-product/{slug}', 'CustomerProductController@customer_product')->name('customer.product');
Route::get('/customer-packages', 'HomeController@premium_package_index')->name('customer_packages_list_show');


Route::get('/product/{slug}', 'HomeController@product')->name('product');
Route::get('/products', 'HomeController@listing')->name('products');
Route::get('/search?category={category_slug}', 'HomeController@search')->name('products.category');
Route::get('/search?subcategory={subcategory_slug}', 'HomeController@search')->name('products.subcategory');
Route::get('/search?subsubcategory={subsubcategory_slug}', 'HomeController@search')->name('products.subsubcategory');
Route::get('/search?brand={brand_slug}', 'HomeController@search')->name('products.brand');
Route::post('/product/variant_price', 'HomeController@variant_price')->name('products.variant_price');	
Route::post('/product/variant_image', 'HomeController@variantImg')->name('products.variant_image');
// Route::get('/shops/visit/{domain}', 'HomeController@shop')->name('shop.visit');
Route::get('/visit/shops/{slug}', 'HomeController@shopVisit')->name('visit.shop');
//Route::group(['domain'=>'{domain}.sheconomy.in'],function(){
//    Route::any('/', 'HomeController@shop'
//        )->name('shop.visit');
//}); 
//view Time Route
Route::post('view_time', 'HomeController@viewTime')->name('view.time');

Route::get('/shops/visit/{slug}/{type}', 'HomeController@filter_shop')->name('shop.visit.type');
Route::get('/shops/visit/{slug}/{type}/{id}', 'HomeController@aboutUs')->name('shop.visit.aboutus');
Route::get('/shops/visit/seller/{slug}/{type}/{id}', 'HomeController@sellerReview')->name('shop.visit.seller_review');
// Post Seller Review
Route::post('/shops/visit/seller/{id}', 'HomeController@postSellerRating')->name('seller.rating');
// End Post Seller Review
Route::get('/shops/{slug}/{type}/{id}', 'HomeController@policies')->name('shop.visit.policies');
Route::get('/shops/seller/{slug}/{type}/{id}', 'HomeController@contactUs')->name('shop.visit.contact_us');

Route::get('/cart', 'CartController@index')->name('cart');
Route::post('/cart/nav-cart-items', 'CartController@updateNavCart')->name('cart.nav_cart');
Route::post('/cart/show-cart-modal', 'CartController@showCartModal')->name('cart.showCartModal');
Route::post('/cart/addtocart', 'CartController@addToCart')->name('cart.addToCart');
Route::post('/cart/removeFromCart', 'CartController@removeFromCart')->name('cart.removeFromCart');
Route::post('/cart/updateQuantity', 'CartController@updateQuantity')->name('cart.updateQuantity');

//Checkout Routes

Route::group(['middleware' => ['checkout']], function(){
	Route::get('/checkout', 'CheckoutController@get_shipping_info')->name('checkout.shipping_info');
	Route::any('/checkout/delivery_info', 'CheckoutController@store_shipping_info')->name('checkout.store_shipping_infostore');
	Route::post('/checkout/payment_select', 'CheckoutController@store_delivery_info')->name('checkout.store_delivery_info');
});

Route::get('/checkout/order-confirmed', 'CheckoutController@order_confirmed')->name('order_confirmed');
Route::post('/checkout/payment', 'CheckoutController@checkout')->name('payment.checkout');
Route::post('/get_pick_ip_points', 'HomeController@get_pick_ip_points')->name('shipping_info.get_pick_ip_points');
Route::get('/checkout/payment_select', 'CheckoutController@get_payment_info')->name('checkout.payment_info');
Route::post('/checkout/apply_coupon_code', 'CheckoutController@apply_coupon_code')->name('checkout.apply_coupon_code');
Route::post('/checkout/remove_coupon_code', 'CheckoutController@remove_coupon_code')->name('checkout.remove_coupon_code');

//Paypal START
Route::get('/paypal/payment/done', 'PaypalController@getDone')->name('payment.done');
Route::get('/paypal/payment/cancel', 'PaypalController@getCancel')->name('payment.cancel');
//Paypal END

// SSLCOMMERZ Start
Route::get('/sslcommerz/pay', 'PublicSslCommerzPaymentController@index');
Route::POST('/sslcommerz/success', 'PublicSslCommerzPaymentController@success');
Route::POST('/sslcommerz/fail', 'PublicSslCommerzPaymentController@fail');
Route::POST('/sslcommerz/cancel', 'PublicSslCommerzPaymentController@cancel');
Route::POST('/sslcommerz/ipn', 'PublicSslCommerzPaymentController@ipn');
//SSLCOMMERZ END

//Stipe Start
Route::get('stripe', 'StripePaymentController@stripe');
Route::post('stripe', 'StripePaymentController@stripePost')->name('stripe.post');
//Stripe END

Route::get('/compare', 'CompareController@index')->name('compare');
Route::get('/compare/reset', 'CompareController@reset')->name('compare.reset');
Route::post('/compare/addToCompare', 'CompareController@addToCompare')->name('compare.addToCompare');

Route::resource('subscribers','SubscriberController');

Route::get('/brands', 'HomeController@all_brands')->name('brands.all');
Route::get('/categories', 'HomeController@all_categories')->name('categories.all');
Route::get('/search', 'HomeController@search')->name('search');
Route::get('/search?q={search}', 'HomeController@search')->name('suggestion.search');
Route::post('/ajax-search', 'HomeController@ajax_search')->name('search.ajax');
Route::post('/config_content', 'HomeController@product_content')->name('configs.update_status');

Route::get('/seller-policy', 'HomeController@sellerpolicy')->name('sellerpolicy');
Route::get('/returns-policy/', 'HomeController@returnpolicy')->name('returnpolicy');
Route::get('/support-policy', 'HomeController@supportpolicy')->name('supportpolicy');
Route::get('/refund-policy/{id}', 'HomeController@refundpolicy')->name('refundpolicy');
Route::get('/terms-of-use', 'HomeController@terms')->name('terms');
Route::get('/contact-us', 'HomeController@Contact_us')->name('contact-us');
Route::post('/send-contact-us', 'HomeController@Send_contact_us')->name('send-contact-us');
Route::get('/terms-and-conditions', 'HomeController@seller_terms')->name('seller_terms');
Route::get('/prohibited_list', 'HomeController@prohibited_list')->name('prohibited_list');
Route::get('/privacy-policy', 'HomeController@privacypolicy')->name('privacypolicy');
Route::get('/about-founder', 'HomeController@aboutFounder')->name('about-founder');
Route::get('/press', 'HomeController@getPress')->name('footer-press');
Route::get('/blog', 'HomeController@getBlog')->name('footer-blog');

Route::group(['middleware' => ['user', 'verified']], function(){
	Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
	Route::post('/dashboard/country', 'HomeController@dashboardCountry')->name('dashboard.country');
	Route::post('/dashboard/sell_in', 'HomeController@dashboardSellin')->name('dashboard.sell_in');
	Route::post('/dashboard/seller_type', 'HomeController@dashboardSellerType')->name('dashboard.seller_type');
	Route::get('/profile', 'HomeController@profile')->name('profile');
	Route::get('/kyc', 'HomeController@kyc')->name('kyc');
	Route::post('/new-user-verification', 'HomeController@new_verify')->name('user.new.verify');
	Route::post('/new-user-email', 'HomeController@update_email')->name('user.change.email');
	Route::post('/customer/update-profile', 'HomeController@customer_update_profile')->name('customer.profile.update');
	Route::post('/seller/update-profile', 'HomeController@seller_update_profile')->name('seller.profile.update');

	Route::get('seller/seller-type', 'ShopController@seller_type')->name('get_seller_type');
	Route::post('seller/update-seller-type', 'ShopController@update_seller_type')->name('update_seller_type');
        //kyc routes
	Route::post('/seller_kyc', 'HomeController@sellerKyc')->name('seller.kyc');
	Route::post('/seller_kyc/non_india', 'HomeController@sellerKycNonIndia')->name('seller.kyc.non.india');
        //payment setup route
        Route::get('/get_seller_payment_setup', 'HomeController@getSellerPaymentSetup')->name('get.payment.setup');
        Route::post('/post_seller_payment_setup', 'HomeController@postSellerPaymentSetup')->name('payment.setup');
        Route::post('/post_razorpay_setup', 'HomeController@postRazorpaySetup')->name('razorpay.setup');
        Route::post('/post_stripe_setup', 'HomeController@postStripeSetup')->name('stripe.setup');
        Route::post('/post_instamojo_setup', 'HomeController@postInstamojoSetup')->name('instamojo.setup');
        //-------------->
        //domain Setup routes
        Route::get('/get_domain_setup', 'HomeController@getDomainSetup')->name('get.domain.setup');
	Route::post('/post_domain_setup', 'HomeController@postDomainSetup')->name('domain.setup');
	Route::post('/post_domain_verify', 'HomeController@domainVerify')->name('domain.verify');
        //----->>>
        //shipping setup routes
        Route::get('/get_shipping_setup', 'ShippingController@getShippingSetup')->name('get.shipping.setup');
        Route::post('/post_shipping_setup', 'ShippingController@postShippingSetup')->name('shipping.setup');
        Route::post('/post_shipping_update', 'ShippingController@postShippingUpdate')->name('shipping.update');
        ///---->>>
        Route::resource('purchase_history','PurchaseHistoryController');
	Route::post('/purchase_history/details', 'PurchaseHistoryController@purchase_history_details')->name('purchase_history.details');
	Route::get('/purchase_history/destroy/{id}', 'PurchaseHistoryController@destroy')->name('purchase_history.destroy');

	Route::resource('wishlists','WishlistController');
	Route::post('/wishlists/remove', 'WishlistController@remove')->name('wishlists.remove');

	Route::get('/wallet', 'WalletController@index')->name('wallet.index');
	Route::post('/recharge', 'WalletController@recharge')->name('wallet.recharge');

	Route::resource('support_ticket','SupportTicketController');
	Route::post('support_ticket/reply','SupportTicketController@seller_store')->name('support_ticket.seller_store');

	Route::post('/customer_packages/purchase', 'CustomerPackageController@purchase_package')->name('customer_packages.purchase');
	Route::resource('customer_products', 'CustomerProductController');
	Route::post('/customer_products/published', 'CustomerProductController@updatePublished')->name('customer_products.published');
	Route::post('/customer_products/status', 'CustomerProductController@updateStatus')->name('customer_products.update.status');

	Route::get('digital_purchase_history', 'PurchaseHistoryController@digital_index')->name('digital_purchase_history.index');
});

Route::get('/customer_products/destroy/{id}', 'CustomerProductController@destroy')->name('customer_products.destroy');
//Tracking Order Via Api---
Route::post('/orders_tracking_api', 'OrderController@postOrderTrackingApi')->name('api.order.tracking');

Route::group(['prefix' =>'seller', 'middleware' => ['seller', 'verified']], function(){
	Route::get('/products', 'HomeController@seller_product_list')->name('seller.products');
	Route::get('/product/upload', 'HomeController@show_product_upload_form')->name('seller.products.upload');
	Route::get('/product/{id}/edit', 'HomeController@show_product_edit_form')->name('seller.products.edit');
	Route::resource('payments','PaymentController');

	Route::get('/shop/apply_for_verification', 'ShopController@verify_form')->name('shop.verify');
	Route::post('/shop/apply_for_verification', 'ShopController@verify_form_store')->name('shop.verify.store');

	Route::get('/reviews', 'ReviewController@seller_reviews')->name('reviews.seller');
	Route::post('/hide/phone/{id}', 'HomeController@hidePhone')->name('hide.seller.phone');
	Route::post('/hide/address/{id}', 'HomeController@hideAddress')->name('hide.seller.address');

	//digital Product
	Route::get('/digitalproducts', 'HomeController@seller_digital_product_list')->name('seller.digitalproducts');
	Route::get('/digitalproducts/upload', 'HomeController@show_digital_product_upload_form')->name('seller.digitalproducts.upload');
	Route::get('/digitalproducts/{id}/edit', 'HomeController@show_digital_product_edit_form')->name('seller.digitalproducts.edit');
});

Route::group(['middleware' => ['auth']], function(){
	Route::post('/products/store/','ProductController@store')->name('products.store');
	Route::post('/products/update/{id}','ProductController@update')->name('products.update');
	Route::get('/products/destroy/{id}', 'ProductController@destroy')->name('products.destroy');
	Route::get('/products/duplicate/{id}', 'ProductController@duplicate')->name('products.duplicate');
	Route::post('/products/sku_combination', 'ProductController@sku_combination')->name('products.sku_combination');
	Route::post('/products/sku_combination_edit', 'ProductController@sku_combination_edit')->name('products.sku_combination_edit');
	Route::post('/products/featured', 'ProductController@updateFeatured')->name('products.featured');
	Route::post('/products/published', 'ProductController@updatePublished')->name('products.published');
	Route::post('make/variant', 'ProductController@makeVariant')->name('make.variant');
	

	Route::get('invoice/customer/{order_id}', 'InvoiceController@customer_invoice_download')->name('customer.invoice.download');
	Route::get('invoice/seller/{order_id}', 'InvoiceController@seller_invoice_download')->name('seller.invoice.download');

	Route::resource('orders','OrderController');
	Route::get('/orders/destroy/{id}', 'OrderController@destroy')->name('orders.destroy');
	Route::post('/orders/details', 'OrderController@order_details')->name('orders.details');
	Route::post('/orders/update_delivery_status', 'OrderController@update_delivery_status')->name('orders.update_delivery_status');
	Route::post('/orders/update_payment_status', 'OrderController@update_payment_status')->name('orders.update_payment_status');

	Route::resource('/reviews', 'ReviewController');

	Route::resource('/withdraw_requests', 'SellerWithdrawRequestController');
	Route::get('/withdraw_requests_all', 'SellerWithdrawRequestController@request_index')->name('withdraw_requests_all');
	Route::post('/withdraw_request/payment_modal', 'SellerWithdrawRequestController@payment_modal')->name('withdraw_request.payment_modal');
	Route::post('/withdraw_request/message_modal', 'SellerWithdrawRequestController@message_modal')->name('withdraw_request.message_modal');

	Route::resource('conversations','ConversationController');
	Route::post('conversations/refresh','ConversationController@refresh')->name('conversations.refresh');
	Route::resource('messages','MessageController');

	//Product Bulk Upload
	Route::get('/product-bulk-upload/index', 'ProductBulkUploadController@index')->name('product_bulk_upload.index');
	Route::post('/bulk-product-upload', 'ProductBulkUploadController@bulk_upload')->name('bulk_product_upload');
	Route::get('/product-csv-download/{type}', 'ProductBulkUploadController@import_product')->name('product_csv.download');
	Route::get('/vendor-product-csv-download/{id}', 'ProductBulkUploadController@import_vendor_product')->name('import_vendor_product.download');
	Route::group(['prefix' =>'bulk-upload/download'], function(){
		Route::get('/category', 'ProductBulkUploadController@pdf_download_category')->name('pdf.download_category');
		Route::get('/sub_category', 'ProductBulkUploadController@pdf_download_sub_category')->name('pdf.download_sub_category');
		Route::get('/sub_sub_category', 'ProductBulkUploadController@pdf_download_sub_sub_category')->name('pdf.download_sub_sub_category');
		Route::get('/brand', 'ProductBulkUploadController@pdf_download_brand')->name('pdf.download_brand');
		Route::get('/seller', 'ProductBulkUploadController@pdf_download_seller')->name('pdf.download_seller');
	});

	//Product Export
	Route::get('/product-bulk-export', 'ProductBulkUploadController@export')->name('product_bulk_export.index');

	Route::resource('digitalproducts','DigitalProductController');
	Route::get('/digitalproducts/destroy/{id}', 'DigitalProductController@destroy')->name('digitalproducts.destroy');
	Route::get('/digitalproducts/download/{id}', 'DigitalProductController@download')->name('digitalproducts.download');
});

Route::post('share/product', 'ProductController@shareProduct')->name('share.product');
Route::post('/shop_address', 'HomeController@shopAddress')->name('shop.address');
Route::resource('shops', 'ShopController');
//routes for shop setting
Route::get('/get_basic_info', 'ShopController@getBasicInfo')->name('shop.basic_info');
Route::get('/get_home_settings', 'ShopController@getHomeSettings')->name('shop.home_settings');
Route::post('/post_home_settings/{id}', 'ShopController@postHomeInfo')->name('shop.post_home_settings');
Route::get('/get_about_us', 'ShopController@getAboutUs')->name('get.shop.about_us');
Route::post('/about_us', 'ShopController@aboutUs')->name('shop.about_us');
Route::get('/get_contact_us', 'ShopController@getContactUs')->name('shop.contact_us');
Route::post('/post_contact_us', 'ShopController@postContactUs')->name('shop.post_contact');
Route::post('/post_contact_us/{id}', 'ShopController@updateContactUs')->name('shop.add_contact_us');

Route::get('/track_your_order', 'HomeController@trackOrder')->name('orders.track');

Route::get('/instamojo/payment/pay-success', 'InstamojoController@success')->name('instamojo.success');

Route::post('rozer/payment/pay-success', 'RazorpayController@payment')->name('payment.rozer');

Route::get('/paystack/payment/callback', 'PaystackController@handleGatewayCallback');

Route::get('/vogue-pay', 'VoguePayController@showForm');
Route::get('/vogue-pay/success/{id}', 'VoguePayController@paymentSuccess');
Route::get('/vogue-pay/failure/{id}', 'VoguePayController@paymentFailure');

//2checkout Start
Route::post('twocheckout/payment/callback', 'TwoCheckoutController@twocheckoutPost')->name('twocheckout.post');
//2checkout END

Route::resource('addresses','AddressController');
Route::get('/addresses/destroy/{id}', 'AddressController@destroy')->name('addresses.destroy');
Route::get('/addresses/set_default/{id}', 'AddressController@set_default')->name('addresses.set_default');

Route::get('/{slug}', 'PageController@show_custom_page')->name('custom-pages.show_custom_page');

//payhere below
Route::get('/payhere/checkout/testing', 'PayhereController@checkout_testing')->name('payhere.checkout.testing');
Route::get('/payhere/wallet/testing', 'PayhereController@wallet_testing')->name('payhere.checkout.testing');
Route::get('/payhere/customer_package/testing', 'PayhereController@customer_package_testing')->name('payhere.customer_package.testing');

Route::any('/payhere/checkout/notify', 'PayhereController@checkout_notify')->name('payhere.checkout.notify');
Route::any('/payhere/checkout/return', 'PayhereController@checkout_return')->name('payhere.checkout.return');
Route::any('/payhere/checkout/cancel', 'PayhereController@chekout_cancel')->name('payhere.checkout.cancel');

Route::any('/payhere/wallet/notify', 'PayhereController@wallet_notify')->name('payhere.wallet.notify');
Route::any('/payhere/wallet/return', 'PayhereController@wallet_return')->name('payhere.wallet.return');
Route::any('/payhere/wallet/cancel', 'PayhereController@wallet_cancel')->name('payhere.wallet.cancel');

Route::any('/payhere/seller_package_payment/notify', 'PayhereController@seller_package_notify')->name('payhere.seller_package_payment.notify');
Route::any('/payhere/seller_package_payment/return', 'PayhereController@seller_package_payment_return')->name('payhere.seller_package_payment.return');
Route::any('/payhere/seller_package_payment/cancel', 'PayhereController@seller_package_payment_cancel')->name('payhere.seller_package_payment.cancel');

Route::any('/payhere/customer_package_payment/notify', 'PayhereController@customer_package_notify')->name('payhere.customer_package_payment.notify');
Route::any('/payhere/customer_package_payment/return', 'PayhereController@customer_package_return')->name('payhere.customer_package_payment.return');
Route::any('/payhere/customer_package_payment/cancel', 'PayhereController@customer_package_cancel')->name('payhere.customer_package_payment.cancel');


Route::get('/facebook/redirect', 'SocialAuthController@redirect')->name('facebook.login');
Route::get('/facebook/callback', 'SocialAuthController@callback')->name('facebook.callback');

Route::get('auth/redirect/google', 'SocialAuthController@redirectGoogle')->name('google.login');
Route::get('auth/google/callback', 'SocialAuthController@callbackGoogle')->name('google.callback');

Route::get('auth/linkedin', 'SocialAuthController@redirectLinkedin')->name('linkedin.login');
Route::get('linkedin/callback', 'SocialAuthController@callbackLinkedin')->name('linkedin.callback');