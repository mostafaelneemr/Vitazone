<!DOCTYPE html>
@php
    $logo = 'logo_'.locale();
    $description = 'description_'.locale();
    $favicon = 'favicon_'.locale();
    $keyword = 'keyword_'.locale();

@endphp
@if(\App\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
    <html dir="rtl" lang="en">
    @else
        <html lang="en">
        @endif
        <head>

            @php
                $seosetting = \App\SeoSetting::first();
                $business_setting_compress = \App\BusinessSetting::where('type', "compress_css")->first();
                $business_setting_merge = \App\BusinessSetting::where('type', "merge_css")->first();
            @endphp

            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="robots" content="index, follow">
         
            <title>@yield('meta_title', \App\GeneralSetting::first()->{'site_name_'.locale()})</title>
            <meta name="description" content="@yield('meta_description', $seosetting->{'description_'.locale()})" />
            <meta name="keywords" content="@yield('meta_keywords', $seosetting->{'keyword_'.locale()})">
            <meta name="author" content="{{ $seosetting->{'author_'.locale()} }}">
            <meta name="sitemap_link" content="{{ $seosetting->sitemap_link }}">

            @yield('meta')

                <meta itemprop="image" content="{{ static_asset(\App\GeneralSetting::first()->$logo) }}">

                <!-- Twitter Card data -->
                <meta name="twitter:card" content="product">
                <meta name="twitter:site" content="@publisher_handle">
{{--                <meta name="twitter:title" content="{{ \App\GeneralSetting::first()->{'site_name_'.locale()} }}">--}}
{{--                <meta name="twitter:description" content="{{ $seosetting->$description }}">--}}
                <meta name="twitter:creator" content="@author_handle">
                <meta name="twitter:image" content="{{ static_asset(\App\GeneralSetting::first()->$logo) }}">

                <!-- Open Graph data -->
{{--                <meta property="og:title" content="{{\App\GeneralSetting::first()->{'site_name_'.locale()} }}" />--}}
                <meta property="og:type" content="website" />
                <meta property="og:url" content="{{ route('home') }}" />
                <meta property="og:image" content="{{ static_asset(\App\GeneralSetting::first()->$logo) }}" />
{{--                <meta property="og:description" content="{{ $seosetting->$description }}" />--}}
                <meta property="og:site_name" content="{{ \App\GeneralSetting::first()->{'site_name_'.locale()} }}" />
                <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
            

        <!-- Favicon -->
        <link type="image/x-icon" href="{{ uploaded_asset(\App\GeneralSetting::first()->$favicon) }}" rel="shortcut icon" />


            @if($business_setting_merge->value == 1)
                <link rel="stylesheet" href="{{ static_asset('css/all.css') }}" type="text/css" >
                <script src="{{ static_asset('frontend/js/vendor/jquery.min.js') }}"></script>
                <link rel="stylesheet" href="{{ static_asset('frontend/css/font-awesome.min.css') }}" type="text/css" media="none" onload="if(media!='all')media='all'">
                <link rel="stylesheet" href="{{ static_asset('frontend/css/line-awesome.min.css') }}" type="text/css" media="none" onload="if(media!='all')media='all'">
            @elseif($business_setting_merge->value == 0)
                <link rel="stylesheet" href="{{ static_asset('frontend/css/bootstrap.min.css') }}" type="text/css" media="all">

                <!-- Icons -->
                <link rel="stylesheet" href="{{ static_asset('frontend/css/font-awesome.min.css') }}" type="text/css" media="none" onload="if(media!='all')media='all'">
                <link rel="stylesheet" href="{{ static_asset('frontend/css/line-awesome.min.css') }}" type="text/css" media="none" onload="if(media!='all')media='all'">

                <link type="text/css" href="{{ static_asset('frontend/css/bootstrap-tagsinput.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
                <link type="text/css" href="{{ static_asset('frontend/css/jodit.min.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
                <link type="text/css" href="{{ static_asset('frontend/css/sweetalert2.min.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
                <link type="text/css" href="{{ static_asset('frontend/css/slick.css') }}" rel="stylesheet" media="all">
                <link type="text/css" href="{{ static_asset('frontend/css/xzoom.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
                <link type="text/css" href="{{ static_asset('frontend/css/jssocials.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
                <link type="text/css" href="{{ static_asset('frontend/css/jssocials-theme-flat.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
                <link type="text/css" href="{{ static_asset('frontend/css/intlTelInput.min.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
                <link type="text/css" href="{{ static_asset('frontend/css/active-shop.css') }}" rel="stylesheet" media="all">


                <link type="text/css" href="{{ static_asset('frontend/css/main.css') }}" rel="stylesheet" media="all">

                <!-- Custom style -->
                <link type="text/css" href="{{ static_asset('frontend/css/custom-style.css') }}" rel="stylesheet" media="all">

                <!-- jQuery -->
                <script src="{{ static_asset('frontend/js/vendor/jquery.min.js') }}"></script>

            @endif

            <link type="image/x-icon" href="{{ static_asset(\App\GeneralSetting::first()->favicon) }}" rel="shortcut icon" />

            <!-- Fonts -->
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet" media="none" onload="if(media!='all')media='all'">

            <!-- Bootstrap -->

            <link type="text/css" href="{{ static_asset('css/spectrum.css')}}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">

            <!-- Global style (main) -->

            @if(\App\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
            <!-- RTL -->
                <link type="text/css" href="{{ static_asset('frontend/css/active.rtl.css') }}" rel="stylesheet" media="all">
            @endif

        <!-- color theme -->
            <link href="{{ static_asset('frontend/css/colors/'.\App\GeneralSetting::first()->frontend_color.'.css')}}" rel="stylesheet" media="all">



            @if (\App\BusinessSetting::where('type', 'google_analytics')->first()->value == 1)
            <!-- Global site tag (gtag.js) - Google Analytics -->
                <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('TRACKING_ID') }}"></script>

                <script>
                    window.dataLayer = window.dataLayer || [];
                    function gtag(){dataLayer.push(arguments);}
                    gtag('js', new Date());
                    gtag('config', '{{ env('TRACKING_ID') }}');
                </script>
            @endif

            @if (\App\BusinessSetting::where('type', 'facebook_pixel')->first()->value == 1)
            <!-- Facebook Pixel Code -->
                <script>
                    !function(f,b,e,v,n,t,s)
                    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                        n.queue=[];t=b.createElement(e);t.async=!0;
                        t.src=v;s=b.getElementsByTagName(e)[0];
                        s.parentNode.insertBefore(t,s)}(window, document,'script',
                        'https://connect.facebook.net/en_US/fbevents.js');
                    fbq('init', {{ env('FACEBOOK_PIXEL_ID') }});
                    fbq('track', 'PageView');
                </script>
                <noscript>
                    <img height="1" width="1" style="display:none"
                         src="https://www.facebook.com/tr?id={{ env('FACEBOOK_PIXEL_ID') }}/&ev=PageView&noscript=1"/>
                </noscript>
                <!-- End Facebook Pixel Code -->
            @endif

        </head>
        <body>


        <!-- MAIN WRAPPER -->
        <div class="body-wrap shop-default shop-cards shop-tech gry-bg">

            <!-- Header -->
            @if (count($errors) > 0)
                @foreach ($errors->all()  as $message)
                    <script type="text/javascript">
                        $(document).on('nifty.ready', function() {
                            showAlert('danger', '{{ $message }}');
                        });
                    </script>
                @endforeach
            @endif


            @yield('content')

            @if (\App\BusinessSetting::where('type', 'facebook_chat')->first()->value == 1)
                <div id="fb-root"></div>
                <!-- Your customer chat code -->
                <div class="fb-customerchat"
                     attribution=setup_tool
                     page_id="{{ env('FACEBOOK_PAGE_ID') }}">
                </div>
            @endif

        </div><!-- END: body-wrap -->

        <!-- SCRIPTS -->
        <!-- <a href="#" class="back-to-top btn-back-to-top"></a> -->

        <!-- Core -->
        @if($business_setting_compress->value == 1)

            <script src="{{ static_asset('frontend/js/vendor/popper.min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/vendor/bootstrap.min.js') }}"></script>

            <!-- Plugins: Sorted A-Z -->
            <script src="{{ static_asset('frontend/js/new/jquery.countdown.min-min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/new/select2.min-min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/new/nouislider.min-min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/new/sweetalert2.min-min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/new/slick.min-min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/new/jssocials.min-min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/new/bootstrap-tagsinput.min-min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/new/jodit.min-min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/new/xzoom.min-min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/new/fb-script-min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/new/lazysizes.min-min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/new/intlTelInput.min-min.js') }}"></script>

            <!-- App JS -->
            <script src="{{ static_asset('frontend/js/new/active-shop-min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/new/main-min.js') }}"></script>


        @elseif($business_setting_compress->value == 0)

            <script src="{{ static_asset('frontend/js/vendor/popper.min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/vendor/bootstrap.min.js') }}"></script>

            <!-- Plugins: Sorted A-Z -->
            <script src="{{ static_asset('frontend/js/jquery.countdown.min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/select2.min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/nouislider.min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/sweetalert2.min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/slick.min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/jssocials.min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/bootstrap-tagsinput.min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/jodit.min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/xzoom.min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/fb-script.js') }}"></script>
            <script src="{{ static_asset('frontend/js/lazysizes.min.js') }}"></script>
            <script src="{{ static_asset('frontend/js/intlTelInput.min.js') }}"></script>

            <!-- App JS -->
            <script src="{{ static_asset('frontend/js/active-shop.js') }}"></script>
            <script src="{{ static_asset('frontend/js/main.js') }}"></script>


        @endif


        <!-- jsDeliver -->

        <!-- cdnjs -->
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>

        @yield('script')

        </body>
        </html>
