@extends('frontend.layouts.app')
@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $customer_product->{'meta_title_' . locale()} }}">
    <meta itemprop="description" content="{{ $customer_product->{'meta_description_' . locale()} }}">
    <meta itemprop="image" content="{{ uploaded_asset($customer_product->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $customer_product->{'meta_title_' . locale()} }}">
    <meta name="twitter:description" content="{{ $customer_product->{'meta_description_' . locale()} }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($customer_product->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($customer_product->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $customer_product->{'meta_title_' . locale()} }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ route('product', $customer_product->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($customer_product->meta_img) }}" />
    <meta property="og:description" content="{{ $customer_product->{'meta_description_' . locale()} }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
    <meta property="og:price:amount" content="{{ single_price($customer_product->unit_price) }}" />
@endsection

@section('content')
    <!-- SHOP GRID WRAPPER -->
    <section class="product-details-area">

        <style>
            #messageeeee{
                text-align: center;
                background-color: #333;
                color: #fff;
                width: 90%;
                z-index: 100;
                margin: auto;
                overflow: auto
            }
        </style>
        @if ($open_app == true)
        <div id="messageeeee">
            <div style="padding: 5px;">
                <div id="inner-message" class="alert alert-error">
                    {{-- <button type="button" class="close" data-dismiss="alert">&times;</button> --}}
                    @if (locale() == 'en')
                    <div class="container">
                        <div class="float-left">
                            <h6>{{ translate('Shop in the app')}}</h6>
                            <p>{{ translate('Available on Google Play')}}</p>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-styled btn-base-1 btn-icon-left strong-700 hov-bounce hov-shaddow" href="{{ route('customer.product',$customer_product->id) }}" target="blank">{{ translate('To App')}}</a>
                        </div>
                    </div>
                    @else
                    <div class="container">
                        <div class="float-right">
                            <h6>{{ translate('Shop in the app')}}</h6>
                            <p>{{ translate('Available on Google Play')}}</p>
                        </div>
                        <div class="float-left">
                            <a class="btn btn-styled btn-base-1 btn-icon-left strong-700 hov-bounce hov-shaddow" href="{{ route('customer.product',$customer_product->id) }}" target="blank">{{ translate('To App')}}</a>
                        </div>
                    </div>
                    @endif
                   
                     
                   
                </div>
            </div>
        </div>
        @endif
        <div class="container">

            <div class="bg-white">

                <!-- Product gallery and Description -->
                <div class="row no-gutters cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-lg-6">
                        <div class="product-gal sticky-top d-flex flex-row-reverse">
                            <div class="product-gal-img">
                                @php
                                    // dd($customer_product->photos);
                                @endphp
                                @if (count(explode(',',$customer_product->photos)))
                                    <img class="xzoom img-fluid"
                                        src="{{ uploaded_asset(explode(',',$customer_product->photos)[0]) }}"
                                        xoriginal="{{ uploaded_asset(explode(',',$customer_product->photos)[0]) }}" />
                                @endif
                            </div>
                            <div class="product-gal-thumb">
                                <div class="xzoom-thumbs">
                                    @if ($customer_product->photos != null)
                                        @foreach (explode(',',$customer_product->photos) as $key => $photo)
                                            <a href="{{ uploaded_asset($photo) }}">
                                                <img class="xzoom-gallery" width="80" src="{{ uploaded_asset($photo) }}"
                                                    @if ($key == 0)
                                                xpreview="{{ uploaded_asset($photo) }}"
                                        @endif>
                                        </a>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <!-- Product description -->
                        <div class="product-description-wrapper">
                            <!-- Product title -->
                            <h2 class="product-title">
                                {{ $customer_product->name }}
                            </h2>

                            <div class="row no-gutters mt-3">

                                @if ($customer_product->unit_discount > 0)
                                    <div class="col-2">
                                        <div class="product-description-label">{{ translate('Price') }}:</div>
                                    </div>
                                    <div class="col-10">
                                        <div class="product-price">
                                            <span style="text-decoration: line-through;color: #6c757d" >
                                                <strong style="color: #17a2b8" >
                                                    {{ single_price($customer_product->unit_price) }}
                                                </strong>
                                            </span>
                                            
                                            @if ($customer_product->unit != null || $customer_product->unit != '')
                                                <span class="piece">/{{ $customer_product->unit }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-2">
                                    </div>
                                    <div class="col-10">
                                        <div class="product-price">
                                            <strong>
                                                {{ single_price($customer_product->unit_price - $customer_product->unit_discount) }}
                                            </strong>
                                            @if ($customer_product->unit != null || $customer_product->unit != '')
                                                <span class="piece">/{{ $customer_product->unit }}</span>
                                            @endif
                                        </div>
                                    </div>

                                @else
                                    <div class="col-2">
                                        <div class="product-description-label">{{ translate('Price') }}:</div>
                                    </div>
                                    <div class="col-10">
                                        <div class="product-price">
                                            <strong>
                                                {{ single_price($customer_product->unit_price) }}
                                            </strong>
                                            @if ($customer_product->unit != null || $customer_product->unit != '')
                                                <span class="piece">/{{ $customer_product->unit }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <ul class="list-group border rounded mt-5">
                                <li class="list-group-item">
                                    <div class="icon-block icon-block--style-3 icon-block--style-3-v2">
                                        <i class="la la-user bg-gray-lighter"></i>
                                        <div class="icon-block-content">
                                            <h3 class="heading heading-6 strong-500">
                                                {{ $customer_product->user->name }}
                                            </h3>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="icon-block icon-block--style-3 icon-block--style-3-v2">
                                        <i class="la la-map-marker bg-gray-lighter"></i>
                                        <div class="icon-block-content">
                                            <h3 class="heading heading-6 strong-500">
                                                {{ $customer_product->{'location_' . locale()} }}
                                            </h3>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item border-bottom-0 c-pointer" onclick="show_number(this)">
                                    <div class="icon-block icon-block--style-3 icon-block--style-3-v2">
                                        <i class="la la-phone bg-base-1"></i>
                                        <div class="icon-block-content">
                                            <h3 class="heading heading-5 strong-700 mb-0">
                                                <span
                                                    class="dummy">{{ str_replace(substr($customer_product->user->phone, 3), 'XXXXXXXX', $customer_product->user->phone) }}</span>
                                                <span class="real d-none">{{ $customer_product->user->phone }}</span>
                                            </h3>
                                            <p class="mb-0">{{ translate('Click to show phone number') }}</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <div class="row no-gutters mt-5">
                                <div class="col-2">
                                    <div class="product-description-label mt-2">{{ translate('Share') }}:</div>
                                </div>
                                <div class="col-10">
                                    <div id="share"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="gry-bg mb-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="product-desc-tab bg-white">
                        <div class="tabs tabs--style-2">
                            <ul class="nav nav-tabs justify-content-center sticky-top bg-white">
                                <li class="nav-item">
                                    <a href="#tab_default_1" data-toggle="tab"
                                        class="nav-link text-uppercase strong-600 active show">{{ translate('Description') }}</a>
                                </li>
                                @if ($customer_product->video_link != null)
                                    <li class="nav-item">
                                        <a href="#tab_default_2" data-toggle="tab"
                                            class="nav-link text-uppercase strong-600">{{ translate('Video') }}</a>
                                    </li>
                                @endif
                                @if ($customer_product->pdf != null)
                                    <li class="nav-item">
                                        <a href="#tab_default_3" data-toggle="tab"
                                            class="nav-link text-uppercase strong-600">{{ translate('Downloads') }}</a>
                                    </li>
                                @endif
                            </ul>

                            <div class="tab-content pt-0">
                                <div class="tab-pane active show" id="tab_default_1">
                                    <div class="py-2 px-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php echo $customer_product->{'description_' . locale()};
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="tab_default_2">
                                    <div class="fluid-paragraph py-2">
                                        <!-- 16:9 aspect ratio -->

                                        <div class="embed-responsive embed-responsive-16by9 mb-5">
                                            @if (strpos($customer_product->video_link, '=') !== false)
                                                @if ($customer_product->video_provider == 'youtube' && $customer_product->video_link != null)
                                                    <iframe class="embed-responsive-item"
                                                        src="https://www.youtube.com/embed/{{ explode('=', $customer_product->video_link)[1] }}"></iframe>
                                                @elseif ($customer_product->video_provider == 'dailymotion' &&
                                                    $customer_product->video_link != null)
                                                    <iframe class="embed-responsive-item"
                                                        src="https://www.dailymotion.com/embed/video/{{ explode('video/', $customer_product->video_link)[1] }}"></iframe>
                                                @elseif ($customer_product->video_provider == 'vimeo' &&
                                                    $customer_product->video_link != null)
                                                    <iframe
                                                        src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $customer_product->video_link)[1] }}"
                                                        width="500" height="281" frameborder="0" webkitallowfullscreen
                                                        mozallowfullscreen allowfullscreen></iframe>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_default_3">
                                    <div class="py-2 px-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a
                                                    href="{{ uploaded_asset($customer_product->pdf) }}">{{ translate('Download') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="mb-4">
        <div class="container">
            <div class="p-4 bg-white shadow-sm">
                @if (isset($customer_product->category))
                    <div class="section-title-1 clearfix">
                        <h3 class="heading-5 strong-700 mb-0 float-left">
                            <span class="mr-4">{{ translate('Other Ads of') }}
                                {{ $customer_product->category->{'name_' . locale()} ?? '' }}</span>
                        </h3>
                        <ul class="inline-links float-right">
                            <li><a href="{{ route('customer_products.category', $customer_product->category->{'slug_' . locale()}) }}"
                                    class="active">{{ translate('View More') }}</a></li>
                        </ul>
                    </div>
                @endif
                <div class="caorusel-box">
                    <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4"
                        data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                        @php
                        $products = filter_customer_products(\App\CustomerProduct::where('category_id',
                        $customer_product->category_id)->where('id', '!=', $customer_product->id)->where('status',
                        '1')->where('published', '1'))->limit(10)->get();
                        @endphp
                        @foreach ($products as $key => $product)
                            <div class="product-card-2 card card-product m-2 shop-cards shop-tech">
                                <div class="card-body p-0">
                                    <div class="card-image">
                                        <a href="{{ route('customer.product', $product->{'slug_' . locale()}) }}"
                                            class="d-block">
                                            <img class="img-fit lazyload mx-auto"
                                                src="{{ my_asset('frontend/images/placeholder.jpg') }}"
                                                data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                alt="{{ $product->{'name_' . locale()} }}">
                                        </a>
                                        </a>
                                    </div>

                                    <div class="p-3">
                                        <div class="price-box">
                                            <span
                                                class="product-price strong-600">{{ single_price($product->unit_price) }}</span>
                                        </div>
                                        <h2 class="product-title p-0 text-truncate-2">
                                            <a
                                                href="{{ route('customer.product', $product->{'slug_' . locale()}) }}">{{ $product->{'name_' . locale()} }}</a>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#share').share({
                showLabel: false,
                showCount: false,
                shares: ["email", "twitter", "facebook", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
            });
        });

        function show_number(el) {
            $(el).find('.dummy').addClass('d-none');
            $(el).find('.real').removeClass('d-none').addClass('d-block');
        }

    </script>
@endsection
