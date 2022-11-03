<section class="slice-sm footer-top-bar bg-white">
    <div class="container sct-inner">
        <div class="row no-gutters">
            <div class="col-lg-3 col-md-6">
                <div class="footer-top-box text-center">
                    @php
                        if (\App\Page::find(\App\Models\AppSettings::find(1)->seller_policy)) {
                            $spSlug = \App\Page::find(\App\Models\AppSettings::find(1)->seller_policy)->{'slug_' . locale()};
                        } else {
                            $spSlug = '';
                        }
                        if (\App\Page::find(\App\Models\AppSettings::find(1)->return_policy)) {
                            $rpSlug = \App\Page::find(\App\Models\AppSettings::find(1)->return_policy)->{'slug_' . locale()};
                        } else {
                            $rpSlug = '';
                        }
                        
                        if (\App\Page::find(\App\Models\AppSettings::find(1)->support_policy)) {
                            $sppSlug = \App\Page::find(\App\Models\AppSettings::find(1)->support_policy)->{'slug_' . locale()};
                        } else {
                            $sppSlug = '';
                        }
                        
                    @endphp
                    <a href="{{ url('/', [$spSlug]) }}">
                        <i class="la la-file-text"></i>
                        <h4 class="heading-5">{{ translate('Seller Policy') }}</h4>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-top-box text-center">
                    <a href="{{ url('/', [$rpSlug]) }}">
                        <i class="la la-mail-reply"></i>
                        <h4 class="heading-5">{{ translate('Return Policy') }}</h4>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-top-box text-center">
                    <a href="{{ url('/', [$sppSlug]) }}">
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
</section>


<!-- FOOTER -->
<footer id="footer" class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                @php
                    $generalsetting = \App\GeneralSetting::first();
                @endphp
                <div class="col-lg-4">
                    <div class="col">
                        <a href="{{ route('home') }}" class="d-block">
                            @if ($generalsetting->{'footer_logo_' . locale()} != null)
                                <img loading="lazy"
                                    src="{{ uploaded_asset($generalsetting->{'footer_logo_' . locale()}) }}"
                                    alt="{{ env('APP_NAME') }}" height="44">
                            @elseif($generalsetting->logo != null)
                                <img loading="lazy" src="{{ uploaded_asset($generalsetting->{'logo_' . locale()}) }}"
                                    alt="{{ env('APP_NAME') }}" height="44">
                            @else
                                <img loading="lazy" src="{{ my_asset('frontend/images/logo/logo.png') }}"
                                    alt="{{ env('APP_NAME') }}" height="44">
                            @endif
                        </a>
                        <p class="mt-3">{{ $generalsetting->description }}</p>
                        <div class="d-inline-block d-md-block">
                            <form class="form-inline" method="POST" action="{{ route('subscribers.store') }}">
                                @csrf
                                <div class="form-group mb-0">
                                    <input type="email" class="form-control"
                                        placeholder="{{ translate('Your Email Address') }}" name="email" required>
                                </div>
                                <button type="submit" class="btn btn-base-1 btn-icon-left">
                                    {{ translate('Subscribe') }}
                                </button>
                            </form>
                        </div>
                    </div>
                    {{-- <br>
                    <div class="paymentIcons">
                        <ul class="inline-links">
                            @foreach (\App\PaymentIcon::get() as $icon)
                                <li>
                                    @if ($icon->link != null)
                                        <a href="{{ $icon->link }}">
                                            <img style="margin-left: 1em;margin-right: 1em" loading="lazy"
                                                title="{{ $icon->{'title_' . locale()} }}"
                                                alt="{{ $icon->{'title_' . locale()} }}"
                                                src="{{ uploaded_asset($icon->icon) }}" height="40">
                                        </a>
                                    @else
                                        <img style="margin-left: 1em;margin-right: 1em" loading="lazy"
                                            title="{{ $icon->{'title_' . locale()} }}"
                                            alt="{{ $icon->{'title_' . locale()} }}"
                                            src="{{ uploaded_asset($icon->icon) }}" height="40">
                                    @endif

                                </li>
                            @endforeach

                        </ul>
                    </div> --}}
                </div>
                <div class="col-lg-2">
                    <div class="col text-center text-md-left">
                        <h4 class="heading heading-xs strong-600 text-uppercase mb-2">
                            {{ translate('Contact Info') }}
                        </h4>
                        <ul class="footer-links contact-widget">
                            <li>
                                <span class="d-block opacity-5">{{ translate('Address') }}:</span>
                                <span class="d-block">{{ $generalsetting->{'address_' . locale()} }}</span>
                            </li>
                            <li>
                                <span class="d-block opacity-5">{{ translate('Phone') }}:</span>
                                <span class="d-block">{{ $generalsetting->phone }}</span>
                            </li>
                            <li>
                                <span class="d-block opacity-5">{{ translate('Email') }}:</span>
                                <span class="d-block">
                                    <a href="mailto:{{ $generalsetting->email }}">{{ $generalsetting->email }}</a>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="col text-center text-md-left">
                        <h4 class="heading heading-xs strong-600 text-uppercase mb-2">
                            {{ translate('About the site') }}
                        </h4>
                        <ul class="footer-links">
                            @foreach (\App\Link::where('links_about', 0)->get() as $key => $link)
                                <li>
                                    <a href="{{ $link->{'url_' . locale()} }}" title="">
                                        {{ $link->{'name_' . locale()} }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="col text-center text-md-left">
                        <h4 class="heading heading-xs strong-600 text-uppercase mb-2">
                            {{ translate('Links of site') }}
                        </h4>
                        <ul class="footer-links">

                            @foreach (\App\Link::where('links_about', 1)->get() as $key => $link)
                                <li>
                                    <a href="{{ $link->{'url_' . locale()} }}" title="">
                                        {{ $link->{'name_' . locale()} }}
                                    </a>
                                </li>
                            @endforeach
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

                <div class="col-lg-2">
                    <div class="col text-center text-md-left">
                        <h4 class="heading heading-xs strong-600 text-uppercase mb-2">
                            {{ translate('My Account') }}
                        </h4>

                        <ul class="footer-links">
                            @if (Auth::check())
                                <li>
                                    <a href="{{ route('logout') }}">
                                        {{ translate('Logout') }}
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('user.login') }}">
                                        {{ translate('Login') }}
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('purchase_history.index') }}">
                                    {{ translate('Order History') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('wishlists.index') }}">
                                    {{ translate('My Wishlist') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('orders.track') }}">
                                    {{ translate('Track Order') }}
                                </a>
                            </li>
                            <li>
                                <a href="https://vitazonei.com/articles/" title="">
                                    {{ translate('Blog') }}
                                </a>
                            </li>

                        </ul>
                    </div>

                    @if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null &&
                        \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated)
                        <div class="col text-center text-md-left">
                            <div class="mt-4">
                                <h4 class="heading heading-xs strong-600 text-uppercase mb-2">
                                    {{ translate('Be an affiliate') }}
                                </h4>
                                <a href="{{ route('affiliate.apply') }}" class="btn btn-base-1 btn-icon-left">
                                    {{ translate('Apply Now') }}
                                </a>
                            </div>
                        </div>
                    @endif

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
                                © {{ date('Y') }} {{ $generalsetting->{'description_' . locale()} }}
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="text-center my-3 my-md-0 social-nav model-2">
                        @if ($generalsetting->facebook != null)
                            <li>
                                <a href="{{ $generalsetting->facebook }}" class="facebook" target="_blank"
                                    data-toggle="tooltip" data-original-title="Facebook">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                        @endif
                        @if ($generalsetting->instagram != null)
                            <li>
                                <a href="{{ $generalsetting->instagram }}" class="instagram" target="_blank"
                                    data-toggle="tooltip" data-original-title="Instagram">
                                    <i class="fa fa-instagram"></i>
                                </a>
                            </li>
                        @endif
                        @if ($generalsetting->twitter != null)
                            <li>
                                <a href="{{ $generalsetting->twitter }}" class="twitter" target="_blank"
                                    data-toggle="tooltip" data-original-title="Twitter">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                        @endif
                        @if ($generalsetting->youtube != null)
                            <li>
                                <a href="{{ $generalsetting->youtube }}" class="youtube" target="_blank"
                                    data-toggle="tooltip" data-original-title="Youtube">
                                    <i class="fa fa-youtube"></i>
                                </a>
                            </li>
                        @endif
                        @if ($generalsetting->google_plus != null)
                            <li>
                                <a href="{{ $generalsetting->google_plus }}" class="google-plus" target="_blank"
                                    data-toggle="tooltip" data-original-title="Google Plus">
                                    <i class="fa fa-google-plus"></i>
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
                                    <img loading="lazy" alt="paypal"
                                        src="{{ my_asset('frontend/images/icons/cards/paypal.png') }}"
                                        height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'stripe_payment')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="stripe"
                                        src="{{ my_asset('frontend/images/icons/cards/stripe.png') }}"
                                        height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'sslcommerz_payment')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="sslcommerz"
                                        src="{{ my_asset('frontend/images/icons/cards/sslcommerz-foo.png') }}"
                                        height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'instamojo_payment')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="instamojo"
                                        src="{{ my_asset('frontend/images/icons/cards/instamojo.png') }}"
                                        height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'razorpay')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="razorpay"
                                        src="{{ my_asset('frontend/images/icons/cards/rozarpay.png') }}"
                                        height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'voguepay')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="voguepay"
                                        src="{{ my_asset('frontend/images/icons/cards/voguepay.png') }}"
                                        height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'paystack')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="paystack"
                                        src="{{ my_asset('frontend/images/icons/cards/paystack.png') }}"
                                        height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'payhere')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="payhere"
                                        src="{{ my_asset('frontend/images/icons/cards/payhere.png') }}"
                                        height="30">
                                </li>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'cash_payment')->first()->value == 1)
                                <li>
                                    <img loading="lazy" alt="cash on delivery"
                                        src="{{ my_asset('frontend/images/icons/cards/cod.png') }}" height="30">
                                </li>
                            @endif
                            @foreach (\App\PaymentIcon::get() as $icon)
                                <li>
                                    <img loading="lazy" title="{{ $icon->{'title_' . locale()} }}"
                                        alt="{{ $icon->{'title_' . locale()} }}"
                                        src="{{ uploaded_asset($icon->icon) }}" height="30">
                                </li>
                            @endforeach
                            @if (\App\Addon::where('unique_identifier', 'offline_payment')->first() != null &&
                                \App\Addon::where('unique_identifier', 'offline_payment')->first()->activated)
                                @foreach (\App\ManualPaymentMethod::all() as $method)
                                    <li>
                                        <img loading="lazy" alt="{{ $method->heading }}"
                                            src="{{ uploaded_asset($method->photo) }}" height="30">
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
