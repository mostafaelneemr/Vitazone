<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {
	Route::get('/tags/getTags', 'TagsController@getTags')->name('tags.getTags');
	Route::get('/admin', 'HomeController@admin_dashboard')->name('admin.dashboard')->middleware(['auth', 'admin']);
	Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {


		//Blog Section

		Route::resource('blog-category', 'BlogCategoryController')->middleware(['can:19']);
		Route::get('/blog-category/destroy/{id}', 'BlogCategoryController@destroy')->name('blog-category.destroy')->middleware(['can:19']);
		Route::resource('blog', 'BlogController')->middleware(['can:19']);
		Route::get('/blog/destroy/{id}', 'BlogController@destroy')->name('blog.destroy')->middleware(['can:19']);
		Route::post('/blog/change-status', 'BlogController@change_status')->name('blog.change-status')->middleware(['can:19']);

		Route::resource('categories', 'CategoryController')->middleware(['can:18']);
		Route::resource('refundResones', 'RefundResonesController')->middleware(['can:21']);
		Route::get('/refundResones/destroy/{id}', 'RefundResonesController@destroy')->name('refundResones.destroy')->middleware(['can:21']);
		Route::get('/categories/destroy/{id}', 'CategoryController@destroy')->name('categories.destroy')->middleware(['can:18']);
		Route::post('/categories/featured', 'CategoryController@updateFeatured')->name('categories.featured')->middleware(['can:18']);
		Route::post('/categories/published', 'CategoryController@updatePublished')->name('categories.published')->middleware(['can:18']);

		Route::resource('subcategories', 'SubCategoryController');
		Route::get('/subcategories/destroy/{id}', 'SubCategoryController@destroy')->name('subcategories.destroy');

		Route::resource('subsubcategories', 'SubSubCategoryController');
		Route::get('/subsubcategories/destroy/{id}', 'SubSubCategoryController@destroy')->name('subsubcategories.destroy');

		Route::resource('brands', 'BrandController')->middleware(['can:28']);
		Route::group(['prefix' => 'brands', 'middleware' => 'can:28'], function () {
			Route::get('destroy/{id}', 'BrandController@destroy')->name('brands.destroy');
		});

		Route::resource('tags', 'TagsController')->middleware(['can:14']);
		Route::get('/tags/destroy/{id}', 'TagsController@destroy')->name('tags.destroy')->middleware(['can:14']);

		Route::post('/tags/save', 'TagsController@addnew')->name('tags.addnew');

		Route::group(['prefix' => 'products', 'middleware' => 'can:1'], function () {
			Route::get('/product-bulk-upload/index', 'ProductBulkUploadController@index')->name('admin_product_bulk_upload.index');
			Route::get('/product-bulk-export', 'ProductBulkUploadController@export')->name('admin_product_bulk_export.index');
			Route::get('admin', 'ProductController@admin_products')->name('products.admin');
			Route::get('seller', 'ProductController@seller_products')->name('products.seller');
			Route::get('create', 'ProductController@create')->name('products.create');
			Route::get('seller_create', 'ProductController@seller_create')->name('products.seller_create');
			Route::get('admin/{id}/edit', 'ProductController@admin_product_edit')->name('products.admin.edit');
			Route::get('seller/{id}/edit', 'ProductController@seller_product_edit')->name('products.seller.edit');
			Route::post('todays_deal', 'ProductController@updateTodaysDeal')->name('products.todays_deal');
			Route::post('get_products_by_subsubcategory', 'ProductController@get_products_by_subsubcategory')->name('products.get_products_by_subsubcategory');
		});

		Route::resource('sellers', 'SellerController')->middleware(['can:5']);
		Route::get('sellers_ban/{id}', 'SellerController@ban')->name('sellers.ban')->middleware(['can:5']);
		Route::group(['prefix' => 'sellers', 'middleware' => 'can:5'], function () {
			Route::get('destroy/{id}', 'SellerController@destroy')->name('sellers.destroy');
			Route::get('view/{id}/verification', 'SellerController@show_verification_request')->name('sellers.show_verification_request');
			Route::get('approve/{id}', 'SellerController@approve_seller')->name('sellers.approve');
			Route::get('reject/{id}', 'SellerController@reject_seller')->name('sellers.reject');
			Route::get('login/{id}', 'SellerController@login')->name('sellers.login');
			Route::post('payment_modal', 'SellerController@payment_modal')->name('sellers.payment_modal');
		});
		Route::get('/seller/payments', 'PaymentController@payment_histories')->name('sellers.payment_histories')->middleware(['can:5']);
		Route::get('/seller/payments/show/{id}', 'PaymentController@show')->name('sellers.payment_history')->middleware(['can:5']);

		Route::resource('customers', 'CustomerController')->middleware(['can:6']);
		Route::get('customers_ban/{customer}', 'CustomerController@ban')->name('customers.ban')->middleware(['can:6']);
		Route::get('/customers/login/{id}', 'CustomerController@login')->name('customers.login')->middleware(['can:6']);
		Route::get('/customers/destroy/{id}', 'CustomerController@destroy')->name('customers.destroy')->middleware(['can:6']);
		Route::post('/customers/activeCustomer', 'CustomerController@activeCustomer')->name('customers.activeCustomer')->middleware(['can:6']);

		Route::get('/newsletter', 'NewsletterController@index')->name('newsletters.index')->middleware(['can:7']);
		Route::get('/firNotification', 'NewsletterController@firNotification')->name('newsletters.firNotification')->middleware(['can:7']);
		Route::post('/sendFirNotification', 'NewsletterController@sendFirNotification')->name('newsletters.sendFirNotification')->middleware(['can:7']);
		Route::post('/newsletter/send', 'NewsletterController@send')->name('newsletters.send')->middleware(['can:7']);
		Route::post('/newsletter/test/smtp', 'NewsletterController@testEmail')->name('test.smtp')->middleware(['can:7']);

		Route::resource('profile', 'ProfileController');
		Route::group(['prefix' => 'mobile-app'], function () {
			Route::get('mobilAppSettings', 'BusinessSettingsController@mobilAppSettings')->name('mobilAppSettings');
			Route::post('mobil_app_settings_update', 'BusinessSettingsController@mobil_app_settings_update')->name('mobil_app_settings.update');
		});
		Route::group(['prefix' => 'business-settings', 'middleware' => 'can:8'], function () {
			Route::post('update', 'BusinessSettingsController@update')->name('business_settings.update');
			Route::post('updateSitemap', 'BusinessSettingsController@updateSitemap')->name('business_settings.updateSitemap');
			Route::post('update/activation', 'BusinessSettingsController@updateActivationSettings')->name('business_settings.update.activation');
			Route::post('clear/cache', 'BusinessSettingsController@clearCache')->name("clear.cache");
			Route::get('activation', 'BusinessSettingsController@activation')->name('activation.index');
			Route::get('payment-method', 'BusinessSettingsController@payment_method')->name('payment_method.index');
			Route::get('other_configration', 'BusinessSettingsController@other_configration')->name('other_configration');
			Route::post('other_configration/update', 'BusinessSettingsController@other_configration_update')->name('other_configration.update');
			Route::get('file_system', 'BusinessSettingsController@file_system')->name('file_system.index');
			Route::get('social-login', 'BusinessSettingsController@social_login')->name('social_login.index');
			Route::get('smtp-settings', 'BusinessSettingsController@smtp_settings')->name('smtp_settings.index');
			Route::get('google-analytics', 'BusinessSettingsController@google_analytics')->name('google_analytics.index');
			Route::get('google-tags', 'BusinessSettingsController@google_tags')->name('google_tags.index');
			Route::get('google-recaptcha', 'BusinessSettingsController@google_recaptcha')->name('google_recaptcha.index');
			Route::get('facebook-chat', 'BusinessSettingsController@facebook_chat')->name('facebook_chat.index');
			Route::post('env_key_update', 'BusinessSettingsController@env_key_update')->name('env_key_update.update');
			Route::post('payment_method_update', 'BusinessSettingsController@payment_method_update')->name('payment_method.update');
			Route::post('google_analytics', 'BusinessSettingsController@google_analytics_update')->name('google_analytics.update');
			Route::post('google_tags', 'BusinessSettingsController@google_tags_update')->name('google_tags.update');
			Route::post('google_recaptcha', 'BusinessSettingsController@google_recaptcha_update')->name('google_recaptcha.update');
			Route::post('facebook_chat', 'BusinessSettingsController@facebook_chat_update')->name('facebook_chat.update');
			Route::post('facebook_pixel', 'BusinessSettingsController@facebook_pixel_update')->name('facebook_pixel.update');
		});
		Route::group(['prefix' => 'currency', 'middleware' => 'can:8'], function () {
			Route::get('/', 'CurrencyController@currency')->name('currency.index');
			Route::post('update', 'CurrencyController@updateCurrency')->name('currency.update');
			Route::post('/your-currency/update', 'CurrencyController@updateYourCurrency')->name('your_currency.update');
			Route::get('create', 'CurrencyController@create')->name('currency.create');
			Route::post('store', 'CurrencyController@store')->name('currency.store');
			Route::post('currency_edit', 'CurrencyController@edit')->name('currency.edit');
			Route::post('update_status', 'CurrencyController@update_status')->name('currency.update_status');
		});

		Route::get('/verification/form', 'BusinessSettingsController@seller_verification_form')->name('seller_verification_form.index')->middleware(['can:5']);
		Route::post('/verification/form', 'BusinessSettingsController@seller_verification_form_update')->name('seller_verification_form.update')->middleware(['can:5']);
		Route::get('/vendor_commission', 'BusinessSettingsController@vendor_commission')->name('business_settings.vendor_commission')->middleware(['can:5']);
		Route::post('/vendor_commission_update', 'BusinessSettingsController@vendor_commission_update')->name('business_settings.vendor_commission.update')->middleware(['can:5']);

		Route::resource('/languages', 'LanguageController')->middleware(['can:8']);
		Route::group(['prefix' => 'languages', 'middleware' => 'can:8'], function () {
			Route::post('update_rtl_status', 'LanguageController@update_rtl_status')->name('languages.update_rtl_status');
			Route::get('destroy/{id}', 'LanguageController@destroy')->name('languages.destroy');
			Route::get('{id}/edit', 'LanguageController@edit')->name('languages.edit');
			Route::post('{id}/update', 'LanguageController@update')->name('languages.update');
			Route::post('key_value_store', 'LanguageController@key_value_store')->name('languages.key_value_store');
		});
		Route::group(['prefix' => 'frontend_settings', 'middleware' => 'can:9'], function () {
			Route::get('home', 'HomeController@home_settings')->name('home_settings.index');
			Route::post('home/top_10', 'HomeController@top_10_settings')->name('top_10_settings.store');
			Route::post('home/frontPage', 'HomeController@frontPageStore')->name('frontPageStore.store');
			Route::get('/sellerpolicy/{type}', 'PolicyController@index')->name('sellerpolicy.index');
			Route::get('/returnpolicy/{type}', 'PolicyController@index')->name('returnpolicy.index');
			Route::get('/supportpolicy/{type}', 'PolicyController@index')->name('supportpolicy.index');
			Route::get('/terms/{type}', 'PolicyController@index')->name('terms.index');
			Route::get('/privacypolicy/{type}', 'PolicyController@index')->name('privacypolicy.index');
		});
		//Policy Controller
		Route::post('/policies/store', 'PolicyController@store')->name('policies.store');

		Route::group(['prefix' => 'frontend_settings', 'middleware' => 'can:9'], function () {
			Route::resource('sliders', 'SliderController')->except(['index']);
			Route::post('/sliders/updateAll/{id}', 'SliderController@updateAll')->name('sliders.updateAll');
			Route::get('/sliders/destroy/{id}', 'SliderController@destroy')->name('sliders.destroy');

			Route::resource('home_banners', 'BannerController');
			Route::get('/home_banners/create/{position}', 'BannerController@create')->name('home_banners.create');
			Route::post('/home_banners/update_status', 'BannerController@update_status')->name('home_banners.update_status');
			Route::post('/home_banners/update_banner_mobile', 'BannerController@update_banner_mobile')->name('home_banners.update_banner_mobile');
			Route::get('/home_banners/destroy/{id}', 'BannerController@destroy')->name('home_banners.destroy');

			Route::resource('icons', 'PaymentIconController');
			Route::get('/icons/destroy/{id}', 'PaymentIconController@destroy')->name('icons.destroy');

			Route::resource('navlinks', 'NavLinkController');
			Route::get('/navlinks/destroy/{id}', 'NavLinkController@destroy')->name('navlinks.destroy');

			Route::resource('home_categories', 'HomeCategoryController');
			Route::get('/home_categories/destroy/{id}', 'HomeCategoryController@destroy')->name('home_categories.destroy');
			Route::post('/home_categories/update_status', 'HomeCategoryController@update_status')->name('home_categories.update_status');
			Route::post('/home_categories/get_subsubcategories_by_category', 'HomeCategoryController@getSubSubCategories')->name('home_categories.get_subsubcategories_by_category');
		});

		Route::resource('roles', 'RoleController')->middleware(['can:10']);
		Route::get('/roles/destroy/{id}', 'RoleController@destroy')->name('roles.destroy')->middleware(['can:10']);

		Route::resource('staffs', 'StaffController')->middleware(['can:10']);
		Route::get('/staffs/destroy/{id}', 'StaffController@destroy')->name('staffs.destroy')->middleware(['can:10']);

		Route::resource('flash_deals', 'FlashDealController')->middleware(['can:2']);
		Route::group(['prefix' => 'flash_deals', 'middleware' => 'can:2'], function () {
			Route::get('destroy/{id}', 'FlashDealController@destroy')->name('flash_deals.destroy');
			Route::post('update_status', 'FlashDealController@update_status')->name('flash_deals.update_status');
			Route::post('update_featured', 'FlashDealController@update_featured')->name('flash_deals.update_featured');
			Route::post('product_discount', 'FlashDealController@product_discount')->name('flash_deals.product_discount');
			Route::post('product_discount_edit', 'FlashDealController@product_discount_edit')->name('flash_deals.product_discount_edit');
		});

		Route::get('/orders', 'OrderController@admin_orders')->name('orders.index.admin')->middleware(['can:3']);
		Route::get('/orders/{id}/show', 'OrderController@show')->name('orders.show')->middleware(['can:3']);
		Route::get('/sales/{id}/show', 'OrderController@sales_show')->name('sales.show')->middleware(['can:3']);
		Route::get('/orders/destroy/{id}', 'OrderController@destroy')->name('orders.destroy')->middleware(['can:3']);
		Route::get('/sales', 'OrderController@sales')->name('sales.index')->middleware(['can:3']);

		// Seller Orders Nader Gamal01153430338
		Route::get('/seller_orders', 'OrderController@seller_orders')->name('seller_orders.index')->middleware(['can:3']);
		Route::get('/seller_orders/{id}/show', 'OrderController@seller_orders_show')->name('seller_orders.show')->middleware(['can:3']);

		Route::resource('links', 'LinkController')->middleware(['can:9']);
		Route::resource('startPages', 'AppStartPagesController')->middleware(['can:9']);
		Route::post('startPages/updatePromotion', 'AppStartPagesController@updatePromotion')->name('startPages.updatePromotion')->middleware(['can:9']);
		Route::get('/startPages/destroy/{id}', 'LinkController@AppStartPagesController')->name('startPages.destroy')->middleware(['can:9']);
		Route::get('/links/destroy/{id}', 'LinkController@destroy')->name('links.destroy')->middleware(['can:9']);
		Route::post('/links/update_links_about', 'LinkController@update_links_about')->name('links.update_links_about')->middleware(['can:9']);

		Route::resource('generalsettings', 'GeneralSettingController')->middleware(['can:9']);
		Route::get('/logo', 'GeneralSettingController@logo')->name('generalsettings.logo')->middleware(['can:9']);
		Route::post('/logo', 'GeneralSettingController@storeLogo')->name('generalsettings.logo.store')->middleware(['can:9']);
		Route::get('/color', 'GeneralSettingController@color')->name('generalsettings.color')->middleware(['can:9']);
		Route::post('/color', 'GeneralSettingController@storeColor')->name('generalsettings.color.store')->middleware(['can:9']);

		Route::resource('seosetting', 'SEOController')->middleware(['can:11']);

		Route::post('/pay_to_seller', 'CommissionController@pay_to_seller')->name('commissions.pay_to_seller')->middleware(['can:5']);

		//Reports
		Route::group(['prefix' => 'reports', 'middleware' => 'can:17'], function () {
			Route::get('stock_report', 'ReportController@stock_report')->name('stock_report.index');
			Route::get('in_house_sale_report', 'ReportController@in_house_sale_report')->name('in_house_sale_report.index');
			Route::get('seller_sale_report', 'ReportController@seller_sale_report')->name('seller_sale_report.index');
			Route::get('user_search_report', 'ReportController@user_search_report')->name('user_search_report.index');
			Route::get('user_search_report/clear', 'ReportController@user_search_report_clear')->name('user_search_report.clear');
			Route::get('seller_report', 'ReportController@seller_report')->name('seller_report.index');
			Route::get('seller_sale_report', 'ReportController@seller_sale_report')->name('seller_sale_report.index');
			Route::get('wish_report', 'ReportController@wish_report')->name('wish_report.index');
		});

		//Coupons
		Route::resource('coupon', 'CouponController')->middleware(['can:12']);
		Route::post('/coupon/get_form', 'CouponController@get_coupon_form')->name('coupon.get_coupon_form')->middleware(['can:12']);
		Route::post('/coupon/get_form_edit', 'CouponController@get_coupon_form_edit')->name('coupon.get_coupon_form_edit')->middleware(['can:12']);
		Route::get('/coupon/destroy/{id}', 'CouponController@destroy')->name('coupon.destroy')->middleware(['can:12']);

		//Reviews
		Route::get('/reviews', 'ReviewController@index')->name('reviews.index');
		Route::post('/reviews/published', 'ReviewController@updatePublished')->name('reviews.published');

		//Support_Ticket
		Route::group(['prefix' => 'support_ticket', 'middleware' => 'can:13'], function () {
			Route::get('/', 'SupportTicketController@admin_index')->name('support_ticket.admin_index');
			Route::get('{id}/show', 'SupportTicketController@admin_show')->name('support_ticket.admin_show');
			Route::post('reply', 'SupportTicketController@admin_store')->name('support_ticket.admin_store');
		});
		//Pickup_Points
		Route::resource('pick_up_points', 'PickupPointController')->middleware(['can:12']);
		Route::get('/pick_up_points/destroy/{id}', 'PickupPointController@destroy')->name('pick_up_points.destroy')->middleware(['can:12']);


		Route::get('orders_by_pickup_point', 'OrderController@order_index')->name('pick_up_point.order_index')->middleware(['can:3']);
		Route::get('/orders_by_pickup_point/{id}/show', 'OrderController@pickup_point_order_sales_show')->name('pick_up_point.order_show')->middleware(['can:3']);

		Route::get('invoice/admin/{order_id}', 'InvoiceController@admin_invoice_download')->name('admin.invoice.download')->middleware(['can:3']);

		//conversation of seller customer
		Route::get('conversations', 'ConversationController@admin_index')->name('conversations.admin_index')->middleware(['can:16']);
		Route::get('conversations/{id}/show', 'ConversationController@admin_show')->name('conversations.admin_show')->middleware(['can:16']);

		Route::post('/sellers/profile_modal', 'SellerController@profile_modal')->name('sellers.profile_modal')->middleware(['can:5']);
		Route::post('/sellers/approved', 'SellerController@updateApproved')->name('sellers.approved')->middleware(['can:5']);

		Route::resource('attributes', 'AttributeController')->middleware(['can:12']);
		Route::get('/attributes/destroy/{id}', 'AttributeController@destroy')->name('attributes.destroy')->middleware(['can:12']);

		Route::resource('addons', 'AddonController')->middleware(['can:15']);
		Route::post('/addons/activation', 'AddonController@activation')->name('addons.activation')->middleware(['can:15']);

		Route::get('/customer-bulk-upload/index', 'CustomerBulkUploadController@index')->name('customer_bulk_upload.index');
		Route::post('/bulk-user-upload', 'CustomerBulkUploadController@user_bulk_upload')->name('bulk_user_upload');
		Route::post('/bulk-customer-upload', 'CustomerBulkUploadController@customer_bulk_file')->name('bulk_customer_upload');
		Route::get('/user', 'CustomerBulkUploadController@pdf_download_user')->name('pdf.download_user');
		//Customer Package
		Route::resource('customer_packages', 'CustomerPackageController');
		Route::get('/customer_packages/destroy/{id}', 'CustomerPackageController@destroy')->name('customer_packages.destroy');
		//Classified Products
		Route::get('/classified_products', 'CustomerProductController@customer_product_index')->name('classified_products');
		Route::post('/classified_products/published', 'CustomerProductController@updatePublished')->name('classified_products.published');

		//Shipping Configuration
		Route::get('/shipping_configuration', 'BusinessSettingsController@shipping_configuration')->name('shipping_configuration.index')->middleware(['can:12']);
		Route::post('/shipping_configuration/update', 'BusinessSettingsController@shipping_configuration_update')->name('shipping_configuration.update')->middleware(['can:12']);

		Route::resource('pages', 'PageController')->middleware(['can:3']);
		Route::get('/pages/destroy/{id}', 'PageController@destroy')->name('pages.destroy')->middleware(['can:3']);

		Route::resource('countries', 'CountryController')->middleware(['can:12']);
		Route::resource('provinces', 'ProvincesController')->middleware(['can:12']);
		Route::get('/provinces/destroy/{id}', 'ProvincesController@destroy')->name('provinces.destroy')->middleware(['can:12']);
		Route::get('/countries/destroy/{id}', 'CountryController@destroy')->name('countries.destroy')->middleware(['can:12']);
		Route::post('/countries/status', 'CountryController@updateStatus')->name('countries.status')->middleware(['can:12']);
		Route::get('/activity/clear', 'ActivityController@clear')->name('activity.clear')->middleware(['can:20']);
		Route::resource('/activity', 'ActivityController')->middleware(['can:20']);
		Route::resource('/modelsetting', 'ModelSettingController')->middleware(['can:26']);

		/**********************Nader Gamal  */
		Route::resource('phones', 'PhonesController')->middleware(['can:25']);
		Route::post('/phones/update_status', 'PhonesController@update_status')->name('phones.update_status')->middleware(['can:25']);
		Route::get('export', 'PhonesController@export')->middleware(['can:25']);
		Route::get('exportt', 'SellerController@exportt')->middleware(['can:5']);
		Route::get('export', 'CustomerController@export');

		Route::resource('cities', 'CitiesController')->middleware(['can:12']);
		Route::post('/cities/status', 'CitiesController@updateStatus')->name('cities.status')->middleware(['can:12']);
		Route::post('/provinces/status', 'ProvincesController@updateStatus')->name('province.status')->middleware(['can:12']);
		Route::get('/cities/destroy/{id}', 'CitiesController@destroy')->name('cities.destroy')->middleware(['can:12']);

		Route::resource('MediaCenters', 'MediaCentersController');
		Route::get('/MediaCenters/destroy/{id}', 'MediaCentersController@destroy')->name('MediaCenters.destroy');
		Route::get('/dealWithFiles', 'MediaCentersController@dealWithFiles')->name('MediaCenters.dealWithFiles');
		Route::get('/deleteFile', 'MediaCentersController@deleteFile')->name('MediaCenters.deleteFile');
		Route::get('/editFile', 'MediaCentersController@editFile')->name('MediaCenters.editFile');
		Route::post('/updateFile', 'MediaCentersController@updateFile')->name('MediaCenters.updateFile');

		Route::resource('regions', 'RegionsController')->middleware(['can:12']);
		Route::post('/regions/status', 'RegionsController@updateStatus')->name('regions.status')->middleware(['can:12']);
		Route::get('/regions/destroy/{id}', 'RegionsController@destroy')->name('regions.destroy')->middleware(['can:12']);

		Route::post('/pages/update_mobile_appear', 'PageController@update_mobile_appear')->name('pages.update_mobile_appear');

		Route::resource('wallets', 'WalletsController')->middleware(['can:23']);
		Route::get('/wallets/reject/{id}', 'WalletsController@reject')->name('wallets.reject')->middleware(['can:23']);

		// uploaded files
		Route::resource('/uploaded-files', 'AizUploadController')->middleware(['can:22']);
		Route::group(['prefix' => 'uploaded-files', 'middleware' => 'can:22'], function () {
			Route::any('file-info', 'AizUploadController@file_info')->name('uploaded-files.info');
			Route::get('destroy/{id}', 'AizUploadController@destroy')->name('uploaded-files.destroy');
		});
		/********************** */
	});
});
