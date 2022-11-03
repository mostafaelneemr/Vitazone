<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link name="favicon" type="image/x-icon" href="{{static_asset('img/favicon.png')}}" rel="shortcut icon" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="{{ static_asset('css/bootstrap.min.css')}}" rel="stylesheet">

    <!--Font Awesome [ OPTIONAL ]-->
    <link href="{{ static_asset('plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">

    <!--active-shop Stylesheet [ REQUIRED ]-->
    <link href="{{ static_asset('css/active-shop.min.css')}}" rel="stylesheet">

    <!--active-shop Premium Icon [ DEMONSTRATION ]-->
    <link href="{{ static_asset('css/demo/active-shop-demo-icons.min.css')}}" rel="stylesheet">

    <!--Demo [ DEMONSTRATION ]-->
    <link href="{{ static_asset('css/demo/active-shop-demo.min.css') }}" rel="stylesheet">

    <!--Theme [ DEMONSTRATION ]-->
    <link href="{{ static_asset('css/themes/type-c/theme-navy.min.css') }}" rel="stylesheet">

    <link href="{{ static_asset('css/custom.css') }}" rel="stylesheet">

</head>
<body>
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
    <div id="container" class="blank-index"
        @if ($generalsetting->admin_login_background != null)
            style="background-image:url('{{ uploaded_asset($generalsetting->admin_login_background) }}');"
        @else
            style="background-image:url('{{ static_asset('img/bg-img/login-bg.jpg') }}');"
        @endif>
        <div class="cls-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel">
                            <div class="panel-body pad-no">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--jQuery [ REQUIRED ]-->
    <script src=" {{static_asset('js/jquery.min.js') }}"></script>


    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="{{ static_asset('js/bootstrap.min.js') }}"></script>


    <!--active-shop [ RECOMMENDED ]-->
    <script src="{{ static_asset('js/active-shop.min.js') }}"></script>

    <!--Alerts [ SAMPLE ]-->
    <script src="{{static_asset('js/demo/ui-alerts.js') }}"></script>

    @yield('script')

</body>
</html>
