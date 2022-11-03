<!--MAIN NAVIGATION-->
<!--===================================================-->
@php
            $generalsetting = \App\GeneralSetting::first(['*'
            ,'logo_'.locale().' as logo'
            ,'footer_logo_'.locale().' as footer_logo'
            ,'admin_login_background_'.locale().' as admin_login_background'
            ,'admin_login_sidebar_'.locale().' as admin_login_sidebar'
            ,'favicon_'.locale().' as favicon'
            ,'site_name_'.locale().' as site_name'
            ,'address_'.locale().' as address'
            ,'description_'.locale().' as description'
            ,'site_name_'.locale().' as site_name'
            ,'site_name_'.locale().' as site_name'
            ,'site_name_'.locale().' as site_name'
            ,'admin_logo_'.locale().' as admin_logo'
            ]);
        @endphp
<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <a href="{{ route('admin.dashboard') }}" class="d-block text-left">
                @if(get_setting('system_logo_white') != null)
                    <img class="mw-100" src="{{ uploaded_asset($generalsetting->admin_logo) }}" class="brand-icon" alt="{{ $generalsetting->site_name }}">
                @else
                    <img class="mw-100" src="{{ uploaded_asset($generalsetting->admin_logo) }}" class="brand-icon" alt="{{ $generalsetting->site_name }}">
                @endif
            </a>
        </div>
        <!--Menu-->
        <!--================================-->
        <div class="aiz-side-nav-wrap">
            <div class="px-20px mb-3">
                <input class="form-control bg-soft-secondary border-0 form-control-sm text-white" type="text" name="" placeholder="{{ translate('Search in menu') }}" id="menu-search" onkeyup="menuSearch()">
            </div>
            <ul class="aiz-side-nav-list" id="search-menu">
            </ul>
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
                <li class="aiz-side-nav-item">
                    <a href="{{route('admin.dashboard')}}" class="aiz-side-nav-link">
                        <i class="las la-home aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{translate('Dashboard')}}</span>
                    </a>
                </li>
                @can('20')
                    <li class="aiz-side-nav-item">
                        <a href="{{route('activity.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['activity.index','activity.show'])}}">
                            <i class="las la-cog aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{translate('Activity Logs')}}</span>
                        </a>
                    </li>
                @endcan
                        <!-- POS Addon-->
                @if (\App\Addon::where('unique_identifier', 'pos_system')->first() != null && \App\Addon::where('unique_identifier', 'pos_system')->first()->activated)
                @if(Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions)))
                  <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-tasks aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{translate('POS System')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{route('poin-of-sales.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['poin-of-sales.index', 'poin-of-sales.create'])}}">
                                <span class="aiz-side-nav-text">{{translate('POS Manager')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{route('poin-of-sales.activation')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('POS Configuration')}}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            @endif
            @if (Auth::user()->can('1') OR Auth::user()->can('28') OR Auth::user()->can('14') OR Auth::user()->can('18'))
                    <!-- Product Menu -->
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{translate('Products')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
    
                        <!--Submenu-->
                       
                        <ul class="aiz-side-nav-list level-2">
                            @can('28')
                                <li class="aiz-side-nav-item">
                                    <a class="aiz-side-nav-link {{ areActiveRoutes(['brands.index', 'brands.create', 'brands.edit'])}}" href="{{route('brands.index')}}"><span class="aiz-side-nav-text">{{translate('Brand')}}</span></a>
                                </li>
                            @endcan
                            @can('14')
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['tags.index', 'tags.create', 'tags.edit'])}}" href="{{route('tags.index')}}"><span class="aiz-side-nav-text">{{translate('Tags')}}</span></a>
                            </li>
                            @endcan
                            @can('18')
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['categories.index', 'categories.create', 'categories.edit'])}}" href="{{route('categories.index')}}"><span class="aiz-side-nav-text">{{translate('Category')}}</span></a>
                            </li>
                            @endcan
                            @can('1')
                                <li class="aiz-side-nav-item">
                                    <a class="aiz-side-nav-link {{ areActiveRoutes(['products.admin', 'products.create', 'products.admin.edit'])}}" href="{{route('products.admin')}}"><span class="aiz-side-nav-text">{{translate('In House Products')}}</span></a>
                                </li>
                                @if(\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                                    <li class="aiz-side-nav-item">
                                        <a class="aiz-side-nav-link {{ areActiveRoutes(['products.seller', 'products.seller.edit'])}}" href="{{route('products.seller')}}"><span class="aiz-side-nav-text">{{translate('Seller Products')}}</span></a>
                                    </li>
                                @endif
                            @endcan
                           
                            @if(\App\BusinessSetting::where('type', 'classified_product')->first()->value == 1)
                                <li class="aiz-side-nav-item">
                                    <a class="aiz-side-nav-link {{ areActiveRoutes(['classified_products'])}}" href="{{route('classified_products')}}"><span class="aiz-side-nav-text">{{translate('Classified Products')}}</span></a>
                                </li>
                            @endif
                            @can('1')
                            <li class="{{ areActiveRoutes(['admin_product_bulk_upload.index'])}}">
                                <a class="aiz-side-nav-link" href="{{route('admin_product_bulk_upload.index')}}"><span class="aiz-side-nav-text">{{translate('Bulk Import')}}</span></a>
                            </li>
                           
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['admin_product_bulk_export.export'])}}" href="{{route('admin_product_bulk_export.index')}}"><span class="aiz-side-nav-text">{{translate('Bulk Export')}}</span></a>
                            </li>
                            @endcan
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
                             @can('1')
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['reviews.index'])}}" href="{{route('reviews.index')}}"><span class="aiz-side-nav-text">{{translate('Product Reviews')}}</span>@if($review_count > 0)<span class="pull-right badge badge-info">{{ $review_count }}</span>@endif</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
            @endif
    

                @can('2')
                <li class="aiz-side-nav-item">
                    <a href="{{ route('flash_deals.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['flash_deals.index', 'flash_deals.create', 'flash_deals.edit'])}}">
                        <i class="las la-money-bill-alt aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{translate('Flash Deal')}}</span>
                    </a>
                </li>
            
                @endcan
                @can('19')
                <!--Blog System-->
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-bullhorn aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Blog System') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('blog.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['blog.create', 'blog.edit'])}}">
                                <span class="aiz-side-nav-text">{{ translate('All Posts') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('blog-category.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['blog-category.create', 'blog-category.edit'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Categories') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan
            @can('3')
                @php
                    $orders = DB::table('orders')
                            ->orderBy('code', 'desc')
                            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                            ->where('order_details.seller_id', \App\User::where('user_type', 'admin')->first()->id)
                            ->where('orders.viewed', 0)
                            ->select('orders.id')
                            ->distinct()
                            ->count();
                @endphp
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-money-bill aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{translate('Sales')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('sales.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['sales.index', 'sales.show'])}}">
                                    <span class="aiz-side-nav-text">{{translate('All Orders')}}</span>
                                </a>
                            </li>

                            <li class="aiz-side-nav-item">
                                <a href="{{ route('orders.index.admin') }}" class="aiz-side-nav-link {{ areActiveRoutes(['orders.index.admin', 'orders.show'])}}" >
                                    <span class="aiz-side-nav-text">{{translate('Inhouse orders')}}@if($orders > 0)<span class="pull-right badge badge-info">{{ $orders }}</span>@endif</span>
                                </a>
                            </li>
                            @if(\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller_orders.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['seller_orders.index', 'seller_orders.show'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Seller Orders')}}</span>
                                    </a>
                                </li>
                            @endif
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('pick_up_point.order_index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['pick_up_point.order_index','pick_up_point.order_show'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Pick-up Point Order')}}</span>
                                </a>
                            </li>
                    </ul>
                </li>
            @endcan
            @can('21')
                @if (\App\Addon::where('unique_identifier', 'refund_request')->first() != null)
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-retweet aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{translate('Refund Request')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>

                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['refundResones.index', 'refundResones.create','refundResones.edit'])}}" href="{{route('refundResones.index')}}"><span class="aiz-side-nav-text">{{translate('refund Resones')}}</span></a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['refund_requests_all', 'reason_show'])}}" href="{{route('refund_requests_all')}}"><span class="aiz-side-nav-text">{{translate('Refund Requests')}}</span></a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['paid_refund'])}}" href="{{route('paid_refund')}}"><span class="aiz-side-nav-text">{{translate('Approved Refund')}}</span></a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['refund_time_config'])}}" href="{{route('refund_time_config')}}"><span class="aiz-side-nav-text">{{translate('Refund Configuration')}}</span></a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endcan
            @can('5')
                @if(\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-user aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Sellers') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            @php
                                $sellers = \App\Seller::where('verification_status', 0)->where('verification_info', '!=', null)->count();
                            @endphp
                            <a href="{{ route('sellers.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['sellers.index', 'sellers.create', 'sellers.edit', 'sellers.payment_history','sellers.approved','sellers.profile_modal','sellers.show_verification_request'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Seller List') }}</span>
                                @if($sellers > 0)<span class="badge badge-info">{{ $sellers }}</span> @endif
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('sellers.payment_histories') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Seller Payments') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('withdraw_requests_all') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Seller Withdraw Requests') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('business_settings.vendor_commission') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Seller Commission') }}</span>
                            </a>
                        </li>
                        @if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated)
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller_packages.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['seller_packages.index', 'seller_packages.create', 'seller_packages.edit'])}}">
                                    <span class="aiz-side-nav-text">{{ translate('Seller Packages') }}</span>
                                </a>
                            </li>
                        @endif
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller_verification_form.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Seller Verification Form') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            @endcan
           
            @can('22')
                <li class="aiz-side-nav-item">
                    <a href="{{ route('uploaded-files.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['uploaded-files.create'])}}">
                        <i class="las la-folder-open aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Uploaded Files') }}</span>
                    </a>
                </li>
            @endcan
            @can('6')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-user-friends aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Customers') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('customers.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Customer list') }}</span>
                            </a>
                        </li>
                        @if(\App\BusinessSetting::where('type', 'classified_product')->first()->value == 1)
                        <li class="aiz-side-nav-item">
                            <a href="{{route('classified_products')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Classified Products')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('customer_packages.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['customer_packages.index', 'customer_packages.create', 'customer_packages.edit'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Classified Packages') }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
            @endcan
            @php
                $conversation = \App\Conversation::where('receiver_id', Auth::user()->id)->where('receiver_viewed', '1')->get();
            @endphp
            @can('16')
                <li class="aiz-side-nav-item">
                    <a href="{{ route('conversations.admin_index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['conversations.admin_index', 'conversations.admin_show'])}}">
                        <i class="las la-comments aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{translate('Conversations')}}</span>
                        @if (count($conversation) > 0)
                            <span class="badge badge-info">{{ count($conversation) }}</span>
                        @endif
                    </a>
                </li>
            
            @endcan

            @can('17')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-file-alt aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Reports') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('in_house_sale_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['in_house_sale_report.index'])}}">
                                <span class="aiz-side-nav-text">{{ translate('In House Sale Report') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller_sale_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['seller_sale_report.index'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Seller Based Selling Report') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('stock_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['stock_report.index'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Stock Report') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['seller_report.index'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Seller Report') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('wish_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['wish_report.index'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Product Wish Report') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('user_search_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['user_search_report.index'])}}">
                                <span class="aiz-side-nav-text">{{ translate('User Searches') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                @can('7')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-envelope aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{translate('Messaging')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>

                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{route('newsletters.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['newsletters.index'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Messaging') }}</span>
                            </a>
                        </li>
                        @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                            <li class="aiz-side-nav-item">
                                <a href="{{route('sms.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['sms.index'])}}">
                                    <span class="aiz-side-nav-text">{{ translate('SMS') }}</span>
                                </a>
                            </li>
                        @endif
                       
                    </ul>
                </li>
                @endcan

                @can('8')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-dharmachakra aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{translate('Business Settings')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>

                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['activation.index'])}}" href="{{route('activation.index')}}"><span class="aiz-side-nav-text">{{translate('Activation')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['payment_method.index'])}}" href="{{ route('payment_method.index') }}"><span class="aiz-side-nav-text">{{translate('Payment method')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['file_system.index'])}}" href="{{ route('file_system.index') }}"><span class="aiz-side-nav-text">{{translate('File System Configuration')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['smtp_settings.index'])}}" href="{{ route('smtp_settings.index') }}"><span class="aiz-side-nav-text">{{translate('SMTP Settings')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['google_analytics.index'])}}" href="{{ route('google_analytics.index') }}"><span class="aiz-side-nav-text">{{translate('Google Analytics')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['google_tags.index'])}}" href="{{ route('google_tags.index') }}"><span class="aiz-side-nav-text">{{translate('Google Tags')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['google_recaptcha.index'])}}" href="{{ route('google_recaptcha.index') }}"><span class="aiz-side-nav-text">{{translate('Google reCAPTCHA')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['facebook_chat.index'])}}" href="{{ route('facebook_chat.index') }}"><span class="aiz-side-nav-text">{{translate('Facebook Chat & Pixel')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['social_login.index'])}}" href="{{ route('social_login.index') }}"><span class="aiz-side-nav-text">{{translate('Social Media Login')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['currency.index'])}}" href="{{route('currency.index')}}"><span class="aiz-side-nav-text">{{translate('Currency')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['languages.index', 'languages.create', 'languages.store', 'languages.show', 'languages.edit'])}}" href="{{route('languages.index')}}"><span class="aiz-side-nav-text">{{translate('Languages')}}</span></a>
                        </li>
                    </ul>
                </li>
                @endcan
                @if (\App\BusinessSetting::where('type', 'mobile_app')->first()->value == 1)
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-mobile-alt aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{translate('Mobile App')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['mobilAppSettings'])}}" href="{{route('mobilAppSettings')}}"><span class="aiz-side-nav-text">{{translate('Settings')}}</span></a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['startPages.index', 'startPages.create', 'startPages.edit'])}}" href="{{route('startPages.index')}}"><span class="aiz-side-nav-text">{{translate('Start Pages')}}</span></a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('newsletters.firNotification')}}" class="aiz-side-nav-link {{ areActiveRoutes(['newsletters.firNotification'])}}">
                                    <span class="aiz-side-nav-text">{{ translate('Public Notification') }}</span>
                                </a>
                            </li>
                        
                        </ul>
                    </li>
                @endif
               
                @can('9')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-desktop aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{translate('Frontend Settings')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>

                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['home_settings.index', 'home_banners.index', 'sliders.index', 'home_categories.index', 'home_banners.create', 'home_categories.create', 'home_categories.edit', 'sliders.create'])}}" href="{{route('home_settings.index')}}"><span class="aiz-side-nav-text">{{translate('Home')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['pages.index', 'pages.create', 'pages.edit'])}}" href="{{route('pages.index')}}"><span class="aiz-side-nav-text">{{translate('Custom Pages')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['links.index', 'links.create', 'links.edit'])}}" href="{{route('links.index')}}"><span class="aiz-side-nav-text">{{translate('Useful Link')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['generalsettings.index'])}}" href="{{route('generalsettings.index')}}"><span class="aiz-side-nav-text">{{translate('General Settings')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['generalsettings.logo'])}}" href="{{route('generalsettings.logo')}}"><span class="aiz-side-nav-text">{{translate('Logo Settings')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['generalsettings.color'])}}" href="{{route('generalsettings.color')}}"><span class="aiz-side-nav-text">{{translate('Color Settings')}}</span></a>
                        </li>
                    </ul>
                </li>
                @endcan

                @can('12')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-gear aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{translate('E-commerce Setup')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>

                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['attributes.index','attributes.create','attributes.edit'])}}" href="{{route('attributes.index')}}"><span class="aiz-side-nav-text">{{translate('Attribute')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['coupon.index','coupon.create','coupon.edit'])}}" href="{{route('coupon.index')}}"><span class="aiz-side-nav-text">{{translate('Coupon')}}</span></a>
                        </li>
                        <li>
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['pick_up_points.index','pick_up_points.create','pick_up_points.edit'])}}" href="{{route('pick_up_points.index')}}"><span class="aiz-side-nav-text">{{translate('Pickup Point')}}</span></a>
                            </li>
                        </li>
                        <li>
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])}}" href="{{route('shipping_configuration.index')}}"><span class="aiz-side-nav-text">{{translate('Shipping Configuration')}}</span></a>
                            </li>
                        </li>
                        <li>
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['countries.index','countries.edit','countries.update'])}}" href="{{route('countries.index')}}"><span class="aiz-side-nav-text">{{translate('Shipping Countries')}}</span></a>
                            </li>
                        </li>
                        <li>
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['provinces.index','provinces.edit','provinces.update'])}}" href="{{route('provinces.index')}}"><span class="aiz-side-nav-text">{{translate('Provinces')}}</span></a>
                            </li>
                        </li>
                        <li>
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['cities.index','cities.edit','cities.update'])}}" href="{{route('cities.index')}}"><span class="aiz-side-nav-text">{{translate('Cities')}}</span></a>
                            </li>
                        </li>
                        <li>
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['regions.index','regions.edit','regions.update'])}}" href="{{route('regions.index')}}"><span class="aiz-side-nav-text">{{translate('Regions')}}</span></a>
                            </li>
                        </li>
                    </ul>
                </li>
            @endcan

             <!-- Affiliate Addon -->
             @if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated)
             @can('27')
               <li class="aiz-side-nav-item">
                   <a href="#" class="aiz-side-nav-link">
                       <i class="las la-link aiz-side-nav-icon"></i>
                       <span class="aiz-side-nav-text">{{translate('Affiliate System')}}</span>
                       <span class="aiz-side-nav-arrow"></span>
                   </a>
                   <ul class="aiz-side-nav-list level-2">
                       <li class="aiz-side-nav-item">
                           <a href="{{route('affiliate.configs')}}" class="aiz-side-nav-link">
                               <span class="aiz-side-nav-text">{{translate('Affiliate Registration Form')}}</span>
                           </a>
                       </li>
                       <li class="aiz-side-nav-item">
                           <a href="{{route('affiliate.index')}}" class="aiz-side-nav-link">
                               <span class="aiz-side-nav-text">{{translate('Affiliate Configurations')}}</span>
                           </a>
                       </li>
                       <li class="aiz-side-nav-item">
                           <a href="{{route('affiliate.users')}}" class="aiz-side-nav-link {{ areActiveRoutes(['affiliate.users', 'affiliate_users.show_verification_request', 'affiliate_user.payment_history'])}}">
                               <span class="aiz-side-nav-text">{{translate('Affiliate Users')}}</span>
                           </a>
                       </li>
                       <li class="aiz-side-nav-item">
                           <a href="{{route('refferals.users')}}" class="aiz-side-nav-link">
                               <span class="aiz-side-nav-text">{{translate('Referral Users')}}</span>
                           </a>
                       </li>
                       <li class="aiz-side-nav-item">
                           <a href="{{route('affiliate.withdraw_requests')}}" class="aiz-side-nav-link">
                               <span class="aiz-side-nav-text">{{translate('Affiliate Withdraw Requests')}}</span>
                           </a>
                       </li>
                       <li class="aiz-side-nav-item">
                           <a href="{{route('affiliate.logs.admin')}}" class="aiz-side-nav-link">
                               <span class="aiz-side-nav-text">{{translate('Affiliate Logs')}}</span>
                           </a>
                       </li>
                   </ul>
               </li>
             @endcan
         @endif
         @can('23')
            @if (\App\Addon::where('unique_identifier', 'offline_payment')->first() != null)
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-bank aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{translate('Offline Payment System')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>

                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['manual_payment_methods.index', 'manual_payment_methods.create', 'manual_payment_methods.edit'])}}" href="{{ route('manual_payment_methods.index') }}"><span class="aiz-side-nav-text">{{translate('Manual Payment Methods')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['offline_wallet_recharge_request.index'])}}" href="{{ route('offline_wallet_recharge_request.index') }}"><span class="aiz-side-nav-text">{{translate('Offline Wallet Rechage')}}</span></a>
                        </li>
                            <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['wallets.index'])}}" href="{{ route('wallets.index') }}"><span class="aiz-side-nav-text">{{translate('Wallet Management')}}</span></a>
                        </li>
                        @if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated)
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['offline_seller_package_payment_request.index'])}}" href="{{ route('offline_seller_package_payment_request.index') }}"><span class="aiz-side-nav-text">{{translate('Offline Seller Package Payment')}}</span></a>
                            </li>
                        @endif
                        @if(\App\BusinessSetting::where('type', 'classified_product')->first()->value == 1)
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['offline_customer_package_payment_request.index'])}}" href="{{ route('offline_customer_package_payment_request.index') }}"><span class="aiz-side-nav-text">{{translate('Offline Customer Package Payment')}}</span></a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endcan

            @if (\App\Addon::where('unique_identifier', 'paytm')->first() != null)
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                            <i class="las la-mobile aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{translate('Paytm Payment Gateway')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>

                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['paytm.index'])}}" href="{{route('paytm.index')}}"><span class="aiz-side-nav-text">{{translate('Set Paytm Credentials')}}</span></a>
                            </li>
                        </ul>
                    </li>
            @endif
             <!-- Club Point Addon-->
             @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                @can('24')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="lab la-btc aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{translate('Club Point System')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('club_points.configs') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Club Point Configurations')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{route('set_product_points')}}" class="aiz-side-nav-link {{ areActiveRoutes(['set_product_points', 'product_club_point.edit'])}}">
                                <span class="aiz-side-nav-text">{{translate('Set Product Point')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{route('club_points.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['club_points.index', 'club_point.details'])}}">
                                <span class="aiz-side-nav-text">{{translate('User Points')}}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan
           @endif
          
            @can('25')
            @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null)
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                            <i class="las la-mobile aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{translate('OTP System')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>

                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['otp.configconfiguration'])}}" href="{{route('otp.configconfiguration')}}"><span class="aiz-side-nav-text">{{translate('OTP Configurations')}}</span></a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['otp_credentials.index'])}}" href="{{route('otp_credentials.index')}}"><span class="aiz-side-nav-text">{{translate('Set OTP Credentials')}}</span></a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link {{ areActiveRoutes(['phones.index'])}}" href="{{route('phones.index')}}"><span class="aiz-side-nav-text">{{translate('Phones')}}</span></a>
                            </li>
                        </ul>
                </li>
            @endif
            @endcan

            @can('13')
                @php
                    $support_ticket = DB::table('tickets')
                                ->where('viewed', 0)
                                ->select('id')
                                ->count();
                @endphp
                <li class="aiz-side-nav-item">
                    <a class="aiz-side-nav-link {{ areActiveRoutes(['support_ticket.admin_index', 'support_ticket.admin_show'])}}" href="{{ route('support_ticket.admin_index') }}">
                        <i class="las la-ticket-alt aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{translate('Support Ticket')}} @if($support_ticket > 0)<span class="pull-right badge badge-info">{{ $support_ticket }}</span>@endif</span>
                    </a>
                </li>
             @endcan

            @can('11')
                <li class="aiz-side-nav-item">
                    <a class="aiz-side-nav-link {{ areActiveRoutes(['seosetting.index'])}}" href="{{ route('seosetting.index') }}">
                        <i class="las la-search aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{translate('SEO Setting')}}</span>
                    </a>
                </li>
            @endcan

            @can('10')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-user aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{translate('Staffs')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>

                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit'])}}" href="{{ route('staffs.index') }}"><span class="aiz-side-nav-text">{{translate('All staffs')}}</span></a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a class="aiz-side-nav-link {{ areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])}}" href="{{route('roles.index')}}"><span class="aiz-side-nav-text">{{translate('Staff permissions')}}</span></a>
                        </li>
                    </ul>
                </li>
            @endcan

            @can('15')
            <li class="aiz-side-nav-item">
                <a href="{{route('addons.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['addons.index', 'addons.create'])}}">
                    <i class="las la-wrench aiz-side-nav-icon"></i>
                    <span class="aiz-side-nav-text">{{translate('Addon Manager')}}</span>
                </a>
            </li>
            @endcan
            @can('26')
            <li class="aiz-side-nav-item">
                <a class="aiz-side-nav-link" href="{{ route('modelsetting.index') }}">
                    <i class="las la-wrench aiz-side-nav-icon"></i>
                    <span class="aiz-side-nav-text">{{translate('Model Setting')}}</span>
                </a>
            </li>
            @endcan
        </ul>
        </div>
    </div>
    <div class="aiz-sidebar-overlay"></div>
</div>
