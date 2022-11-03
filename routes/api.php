<?php

Route::prefix('v1/auth')->group(function () {
    Route::post('login', 'Api\AuthController@login');
    Route::post('signup', 'Api\AuthController@signup');
    Route::post('activeAccount', 'Api\AuthController@activeAccount');
    Route::post('social-login', 'Api\AuthController@socialLogin');
    Route::post('password/create', 'Api\PasswordResetController@create');
    Route::post('password/email', 'Api\ForgotPasswordController@sendLink');
    Route::post('password/reset', 'Api\ResetPasswordController@resetPass');

    //Forgot password phone
    Route::post('/password/phone/reset', 'Api\OTPVerificationController@reset_password_with_code')->name('password.update.phone');
    Route::get('/verification/phone/code/resend', 'Api\OTPVerificationController@resend_verification_code');
    Route::get('/verification/phone/code/send', 'Api\OTPVerificationController@send_code');
    Route::middleware('auth:api')->group(function () {
        Route::get('logout', 'Api\AuthController@logout');
        Route::get('user', 'Api\AuthController@user');
        Route::post('profile', 'Api\AuthController@update_profile');
        Route::post('update_avatar', 'Api\AuthController@update_avatar');
        Route::post('change_passowrd', 'Api\AuthController@change_passowrd');
        Route::post('change_user_password', 'Api\AuthController@change_user_password');
        //Verofocation phone
        Route::post('/verification', 'Api\OTPVerificationController@verify_phone');
    });
});

Route::prefix('v1')->group(function () {
    /********************Nader Gamal*******************/
    Route::post('paymob_procecced_callback', 'CheckoutController@paymob_procecced_callback')->name('paymob_procecced_callback');
    Route::get('getCountries', 'Api\AdressController@getCountries');
    Route::get('getStaticPage/{id}', 'Api\AdressController@getStaticPage');
    Route::get('getStaticPagesList', 'Api\AdressController@getStaticPagesList');
    Route::get('getProvincesByCountryId/{id}', 'Api\AdressController@getProvincesByCountryId');
    Route::get('getCitiesByProvinceId/{id}', 'Api\AdressController@getCitiesByProvinceId');
    Route::get('getRegionsByCityId/{id}', 'Api\AdressController@getRegionsByCityId');
    Route::post('addNewAdress', 'Api\AdressController@addNewAdress')->middleware('auth:api');
    Route::post('editUserAddress', 'Api\AdressController@editUserAddress')->middleware('auth:api');
    Route::get('getUserOrders', 'Api\OrderController@getUserOrders')->middleware('auth:api');
    Route::get('trackYourOrder/{id}', 'Api\OrderController@trackYourOrder')->middleware('auth:api');
    Route::get('cancelOrder/{order_id}', 'Api\OrderController@cancelOrder')->middleware('auth:api');
    Route::get('getMyAdresses', 'Api\AdressController@getMyAdresses')->middleware('auth:api');
    Route::post('FawryApi', 'Api\FawryController@FawryApi')->middleware('auth:api');
    Route::post('sendOtpCode', 'Api\AdressController@sendOtpCode')->middleware('auth:api');
    Route::post('activePhone', 'Api\AdressController@activePhone')->middleware('auth:api');
    Route::get('getMyVerifiedPhones', 'Api\AdressController@getMyVerifiedPhones')->middleware('auth:api');
    Route::get('set_default_adress/{id}', 'Api\AdressController@set_default_adress')->middleware('auth:api');
    Route::get('delete_user_adress/{id}', 'Api\AdressController@delete_user_adress')->middleware('auth:api');
    Route::get('changeUserLang/{lang}', 'Api\AdressController@changeUserLang')->middleware('auth:api');
    Route::post('getCouponDiscount', 'Api\OrderController@getCouponDiscount')->middleware('auth:api');
    Route::post('refundRequest', 'Api\OrderController@refundRequest')->middleware('auth:api');
    Route::get('getRefundResons', 'Api\OrderController@getRefundResons')->middleware('auth:api');
    Route::get('notificationList', 'Api\NotificationsController@notificationList')->middleware('auth:api');
    Route::get('markAsRead/{id}', 'Api\NotificationsController@markAsRead')->middleware('auth:api');
    Route::apiResource('notifications', 'Api\NotificationsController')->only('destroy')->middleware('auth:api');

    Route::get('ads', 'Api\ProductController@ads')->middleware('auth:api');
    Route::get('allAds', 'Api\ProductController@allAds');
    Route::get('getAdsRemainingUploads', 'Api\ProductController@getAdsRemainingUploads')->middleware('auth:api');
    Route::get('viewAd/{id}', 'Api\ProductController@viewAd');
    Route::get('deleteAd/{id}', 'Api\ProductController@deleteAd')->middleware('auth:api');
    Route::post('add_ad', 'Api\ProductController@add_ad')->middleware('auth:api');
    Route::post('edit_ad', 'Api\ProductController@edit_ad')->middleware('auth:api');
    Route::get('getPackages', 'Api\OrderController@getPackages')->middleware('auth:api');
    Route::get('getClientPackage', 'Api\OrderController@getClientPackage')->middleware('auth:api');
    Route::post('getShippingCostDuration', 'Api\OrderController@getShippingCostDuration')->middleware('auth:api');
    Route::post('purchaseFreePackage', 'Api\OrderController@purchaseFreePackage')->middleware('auth:api');
    /********************Nader Gamal*******************/
    Route::apiResource('banners', 'Api\BannerController')->only('index');

    Route::get('brands/top', 'Api\BrandController@top');
    Route::apiResource('brands', 'Api\BrandController')->only('index');

    Route::apiResource('business-settings', 'Api\BusinessSettingController')->only('index');

    Route::get('categories/featured', 'Api\CategoryController@featured');
    Route::get('categories/getMainCategories', 'Api\CategoryController@getMainCategories');
    Route::get('categories/subCategoriesTop/{category_id}', 'Api\CategoryController@subCategoriesTop');
    Route::get('categories/SubWithSubSub/{category_id}', 'Api\CategoryController@SubWithSubSub');
    Route::get('categories/allSubSubCategories/{sub_category_id}', 'Api\CategoryController@allSubSubCategories');
    Route::get('categories/getProductsBySubSub/{sub_sub_category_id}', 'Api\CategoryController@getProductsBySubSub');
    Route::get('categories/home', 'Api\CategoryController@home');
    Route::apiResource('categories', 'Api\CategoryController')->only('index');
    Route::get('sub-categories/{id}', 'Api\SubCategoryController@index')->name('subCategories.index');

    Route::apiResource('colors', 'Api\ColorController')->only('index');

    Route::apiResource('currencies', 'Api\CurrencyController')->only('index');

    Route::apiResource('customers', 'Api\CustomerController')->only('show');

    Route::apiResource('general-settings', 'Api\GeneralSettingController')->only('index');

    Route::apiResource('home-categories', 'Api\HomeCategoryController')->only('index');

    Route::get('purchase-history/{id}', 'Api\PurchaseHistoryController@index')->middleware('auth:api');
    Route::get('purchase-history-details/{id}', ' @index')->name('purchaseHistory.details')->middleware('auth:api');
    Route::get('/track_your_order', 'Api\PurchaseHistoryController@trackOrder');
    Route::get('products/admin', 'Api\ProductController@admin');
    Route::get('products/seller', 'Api\ProductController@seller');
    Route::get('products/category/{id}', 'Api\ProductController@category')->name('api.products.category');
    Route::get('products/sub-category/{id}', 'Api\ProductController@subCategory')->name('products.subCategory');
    Route::get('products/sub-sub-category/{id}', 'Api\ProductController@subSubCategory')->name('products.subSubCategory');
    Route::get('products/brand/{id}', 'Api\ProductController@brand')->name('api.products.brand');
    Route::get('products/todays-deal', 'Api\ProductController@todaysDeal');
    Route::get('products/flash-deal', 'Api\ProductController@flashDeal');
    Route::get('products/featured', 'Api\ProductController@featured');
    Route::get('products/best-seller', 'Api\ProductController@bestSeller');
    Route::get('products/related/{id}', 'Api\ProductController@related')->name('products.related');
    Route::get('products/top-from-seller/{id}', 'Api\ProductController@topFromSeller')->name('products.topFromSeller');
    Route::get('products/search', 'Api\ProductController@search');
    Route::post('products/advancedSearch', 'Api\ProductController@advancedSearch');
    Route::post('products/variant/price', 'Api\ProductController@variantPrice');
    Route::get('products/home', 'Api\ProductController@home');
    Route::apiResource('products', 'Api\ProductController')->except(['store', 'update', 'destroy']);

    Route::get('carts/{id}', 'Api\CartController@index')->middleware('auth:api');
    Route::post('carts/add', 'Api\CartController@add')->middleware('auth:api');
    Route::post('carts/change-quantity', 'Api\CartController@changeQuantity')->middleware('auth:api');
    Route::apiResource('carts', 'Api\CartController')->only('destroy')->middleware('auth:api');

    // Nader Gamal
    Route::get('reviews/product/{id}', 'Api\ReviewController@index')->name('api.reviews.index');
    Route::post('reviews/insertProductReview', 'Api\ReviewController@insertProductReview')->name('api.reviews.insertProductReview')->middleware('auth:api');

    Route::get('shop/user/{id}', 'Api\ShopController@shopOfUser')->middleware('auth:api');
    Route::get('shops/details/{id}', 'Api\ShopController@info')->name('shops.info');
    Route::get('shops/products/all/{id}', 'Api\ShopController@allProducts')->name('shops.allProducts');
    Route::get('shops/oneShop/{id}', 'Api\ShopController@oneShop')->name('shops.oneShop');
    Route::get('shops/products/top/{id}', 'Api\ShopController@topSellingProducts')->name('shops.topSellingProducts');
    Route::get('shops/products/featured/{id}', 'Api\ShopController@featuredProducts')->name('shops.featuredProducts');
    Route::get('shops/products/new/{id}', 'Api\ShopController@newProducts')->name('shops.newProducts');
    Route::get('shops/brands/{id}', 'Api\ShopController@brands')->name('shops.brands');
    Route::apiResource('shops', 'Api\ShopController')->only('index');

    Route::apiResource('sliders', 'Api\SliderController')->only('index');

    Route::get('wishlists/{id}', 'Api\WishlistController@index')->middleware('auth:api');
    Route::post('wishlists/check-product', 'Api\WishlistController@isProductInWishlist')->middleware('auth:api');
    Route::post('wishlists/storeFav', 'Api\WishlistController@storeFav')->middleware('auth:api');
    Route::get('wishlists/destroyfromFav/{id}', 'Api\WishlistController@destroyfromFav')->middleware('auth:api');
    Route::apiResource('wishlists', 'Api\WishlistController')->except(['index', 'update', 'show'])->middleware('auth:api');

    Route::apiResource('settings', 'Api\SettingsController')->only('index');

    Route::get('policies/seller', 'Api\PolicyController@sellerPolicy')->name('policies.seller');
    Route::get('policies/support', 'Api\PolicyController@supportPolicy')->name('policies.support');
    Route::get('policies/return', 'Api\PolicyController@returnPolicy')->name('policies.return');
    Route::get('appStartPages', 'Api\PolicyController@appStartPages')->name('appStartPages');

    Route::get('user/info/{id}', 'Api\UserController@info')->middleware('auth:api');
    Route::post('user/info/update', 'Api\UserController@updateName')->middleware('auth:api');
    Route::post('user/shipping/update', 'Api\UserController@updateShippingAddress')->middleware('auth:api');

    Route::post('coupon/apply', 'Api\CouponController@apply')->middleware('auth:api');

    Route::post('payments/pay/stripe', 'Api\StripeController@processPayment')->middleware('auth:api');
    Route::post('payments/pay/paypal', 'Api\PaypalController@processPayment')->middleware('auth:api');
    Route::post('payments/pay/cod', 'Api\PaymentController@cashOnDelivery')->middleware('auth:api');

    Route::post('order/store', 'Api\OrderController@store')->middleware('auth:api');
    Route::get('tickets', 'Api\TicketsController@index')->middleware('auth:api');
    Route::post('tickets/store', 'Api\TicketsController@store')->middleware('auth:api');
    Route::post('tickets/replay', 'Api\TicketsController@replay')->middleware('auth:api');
    Route::get('tickets/getTicket/{id}', 'Api\TicketsController@getTicket')->middleware('auth:api');
    Route::get('policies', 'Api\UserController@policies');
    Route::get('unpaid/orders', 'Api\PurchaseHistoryDetailController@unpaidOrders')->middleware('auth:api');
    Route::get('paid/orders', 'Api\PurchaseHistoryDetailController@paidOrders')->middleware('auth:api');
    Route::get('toshipped/orders', 'Api\PurchaseHistoryDetailController@toBeShippedOrders')->middleware('auth:api');
    Route::get('shipped/orders', 'Api\PurchaseHistoryDetailController@shippedOrders')->middleware('auth:api');
    Route::get('UserBalance', 'Api\OrderController@UserBalance')->middleware('auth:api');
    Route::get('walletHistory', 'Api\OrderController@walletHistory')->middleware('auth:api');
    Route::get('payOrderWithWallet/{order_id}', 'Api\OrderController@payOrderWithWallet')->middleware('auth:api');
});


Route::middleware('api.seller')->group(function () {
    Route::prefix('v1/vendors/')->group(function () {
        Route::get('products', 'Api\Vender\ProductController@index')->name('api.seller.products')->middleware('api.seller');
        Route::post('product/store', 'Api\Vender\ProductController@store')->name('api.seller.product.store')->middleware('api.seller');
        Route::post('product/update', 'Api\Vender\ProductController@update')->name('api.seller.product.update')->middleware('api.seller');
        Route::get('product/destroy/{id}', 'Api\Vender\ProductController@destroy')->name('api.seller.product.destroy')->middleware('api.seller');
        Route::get('product/getProductForEdit/{id}', 'Api\Vender\ProductController@getProductForEdit')->name('api.seller.product.getProductForEdit')->middleware('api.seller');
        Route::post('product/bulk_upload', 'Api\Vender\ProductController@bulk_upload')->name('api.seller.product.bulk_upload')->middleware('api.seller');
        Route::get('refundRequests', 'Api\Vender\ProductController@refundRequests')->name('api.seller.product.refundRequests')->middleware('api.seller');
        Route::get('seller_reviews', 'Api\Vender\ProductController@seller_reviews')->name('api.seller.product.seller_reviews')->middleware('api.seller');

        Route::get('product/export', 'Api\Vender\ProductController@export')->name('api.seller.product.export')->middleware('api.seller');

        Route::get('customer_products', 'Api\Vender\ProductController@customer_products_index')->name('api.seller.customer_products')->middleware('api.seller');
        Route::post('customer_products/store', 'Api\Vender\ProductController@customer_products_store')->name('api.seller.customer_products.store')->middleware('api.seller');
        //PurchaseHistoryController
        Route::get('purchase_history', 'Api\Vender\PurchaseHistoryController@index')->name('api.purchase_history')->middleware('api.seller');
        Route::get('purchase_history_digital', 'Api\Vender\PurchaseHistoryController@digital_index')->name('api.purchase_history.digital')->middleware('api.seller');
        Route::post('purchase_history_details/store', 'Api\Vender\PurchaseHistoryController@purchase_history_details')->name('api.purchase_history_details')->middleware('api.seller');
        ///

        ///wishlist
        Route::get('wishlists', 'Api\Vender\WishlistController@index')->name('api.wishlists')->middleware('api.seller');
        Route::post('wishlist/store', 'Api\Vender\WishlistController@store')->name('api.wishlists.store')->middleware('api.seller');
        Route::post('wishlist/remove', 'Api\Vender\WishlistController@remove')->name('api.wishlists.remove')->middleware('api.seller');

        //Order
        Route::post('orders', 'Api\Vender\OrderController@index')->name('api.seller.orders')->middleware('api.seller');
        Route::post('order_details', 'Api\Vender\OrderController@order_details')->name('api.seller.order_details')->middleware('api.seller');
        Route::post('update_seller_status', 'Api\Vender\OrderController@update_seller_status')->name('api.seller.update_seller_status')->middleware('api.seller');

        Route::get('shop_setting', 'Api\Vender\ShopController@index')->name('api.shop_setting')->middleware('api.seller');
        Route::post('shop/edit_setting', 'Api\Vender\ShopController@edit_setting')->name('api.shop.edit_setting')->middleware('api.seller');

        Route::get('dashboard', 'Api\Vender\DashboardController@index')->name('api.dashboard')->middleware('api.seller');

        ///WITHDRAW
        Route::post('withdraw/', 'Api\Vender\WithdrawController@withdraw')->name('api.withdraw')->middleware('api.seller');
        Route::get('withdraws_all', 'Api\Vender\WithdrawController@index')->name('api.withdraw.all')->middleware('api.seller');

        Route::get('profile', 'Api\Vender\ProfileController@index')->name('api.profile_seller')->middleware('api.seller');
        Route::patch('profile_update', 'Api\Vender\ProfileController@update')->name('api.profile_seller.update')->middleware('api.seller');

        Route::get('payments', 'Api\Vender\PaymentController@index')->name('api.payments.all')->middleware('api.seller');

        Route::get('conversations', 'Api\Vender\ConversationController@index')->name('api.conversations.all')->middleware('api.seller');


        Route::get('tickets', 'Api\Vender\TicketController@index')->name('api.ticket.all')->middleware('api.seller');
        Route::post('ticket/store', 'Api\Vender\TicketController@store')->name('api.ticket.store')->middleware('api.seller');

        Route::post('recharge', 'Api\Vender\WalletController@recharge')->name('wallet.recharge')->middleware('api.seller');
        Route::get('wallet_history', 'Api\Vender\WalletController@wallet_history')->name('api.wallet.history')->middleware('api.seller');

        Route::get('reviews', 'Api\Vender\ReviewController@index')->name('api.review.all')->middleware('api.seller');
    });
});


Route::fallback(function () {
    return response()->json([
        'data' => [],
        'success' => false,
        'status' => 404,
        'message' => 'Invalid Route'
    ]);
});
