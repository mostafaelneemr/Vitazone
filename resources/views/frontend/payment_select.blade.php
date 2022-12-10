@extends('frontend.layouts.app')

@section('content')

    <div id="page-content">
        <section class="slice-xs sct-color-2 border-bottom">
            <div class="container container-sm">
                <div class="row cols-delimited justify-content-center">
                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center ">
                            <div class="block-icon c-gray-light mb-0">
                                <i class="la la-shopping-cart"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">
                                    {{ translate('1. My Cart') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center ">
                            <div class="block-icon mb-0 c-gray-light">
                                <i class="la la-truck"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">
                                    {{ translate('2. Delivery info') }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center active">
                            <div class="block-icon mb-0">
                                <i class="la la-credit-card"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">
                                    {{ translate('3. Payment') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="py-3 gry-bg">
            <div class="container">
                <div class="row cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-lg-8">
                        <form action="{{ route('payment.checkout') }}" class="form-default" data-toggle="validator"
                            role="form" method="POST" id="checkout-form">
                            @csrf
                            <div class="card">
                                <div class="card-title px-4 py-3">
                                    <h3 class="heading heading-5 strong-500">
                                        {{ translate('Select a payment option') }}
                                    </h3>
                                </div>
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col-md-6 mx-auto">
                                            <div class="row">

                                                @if(Auth::check())
                                                @if (\App\BusinessSetting::where('type', 'paymob')->first()->value == 1)
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="paymob_visa_master_card">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="paymob_visa_master_card" class="online_payment" checked>
                                                            <span>
                                                                <img style="height: 107px !important;" loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/mastercard-visa-card.png') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @endif


                                                @if(Auth::check())
                                                @if (\App\BusinessSetting::where('type', 'paymob')->first()->value == 1)
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="paymob_bank_instalments">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="paymob_bank_instalments" class="online_payment"
                                                                checked>
                                                            <span>
                                                                <img style="height: 107px !important;" loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/bank_installments.webp') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @endif

                                                @if(Auth::check())
                                                @if (\App\BusinessSetting::where('type', 'paymob')->first()->value == 1)
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="paymob_valu">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="paymob_valu" class="online_payment" checked>
                                                            <span>
                                                                <img style="height: 107px !important;" loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/valu.png') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @endif
                                                
                                                @if(Auth::check())
                                                @if (\App\BusinessSetting::where('type', 'paymob')->first()->value == 1)
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="paymob_wallet">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="paymob_wallet" class="online_payment" checked>
                                                            <span>
                                                                <img style="height: 107px !important;" loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/wallet.png') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @endif
                                                    
                                                @if (\App\BusinessSetting::where('type', 'paysky')->first()->value == 1)
                                                    <div class="col-6">

                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="paysky">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="paysky" class="online_payment" checked>
                                                            <span>
                                                                <img style="height: 107px !important;" loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/paysky_' . locale() . '.png') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (\App\BusinessSetting::where('type', 'fawry')->first()->value == 1)
                                                    <div class="col-6">

                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="Fawry">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="fawry" class="online_payment" checked>
                                                            <span>
                                                                <img style="height: 107px !important;" loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/fawry_logo_' . locale() . '.png') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (\App\BusinessSetting::where('type', 'paypal_payment')->first()->value == 1)
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="Paypal">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="paypal" class="online_payment" checked>
                                                            <span>
                                                                <img loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/paypal.png') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (\App\BusinessSetting::where('type', 'stripe_payment')->first()->value == 1)
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="Stripe">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="stripe" class="online_payment" checked>
                                                            <span>
                                                                <img loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/stripe.png') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (\App\BusinessSetting::where('type', 'sslcommerz_payment')->first()->value == 1)
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="sslcommerz">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="sslcommerz" class="online_payment" checked>
                                                            <span>
                                                                <img loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/sslcommerz.png') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (\App\BusinessSetting::where('type', 'instamojo_payment')->first()->value == 1)
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="Instamojo">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="instamojo" class="online_payment" checked>
                                                            <span>
                                                                <img loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/instamojo.png') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (\App\BusinessSetting::where('type', 'razorpay')->first()->value == 1)
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="Razorpay">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="razorpay" class="online_payment" checked>
                                                            <span>
                                                                <img loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/rozarpay.png') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (\App\BusinessSetting::where('type', 'paystack')->first()->value == 1)
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="Paystack">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="paystack" class="online_payment" checked>
                                                            <span>
                                                                <img loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/paystack.png') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (\App\BusinessSetting::where('type', 'voguepay')->first()->value == 1)
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="VoguePay">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="voguepay" class="online_payment" checked>
                                                            <span>
                                                                <img loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/vogue.png') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (\App\BusinessSetting::where('type', 'payhere')->first()->value == 1)
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="payhere">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="payhere" class="online_payment" checked>
                                                            <span>
                                                                <img loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/payhere.png') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (\App\BusinessSetting::where('type', 'ngenius')->first()->value == 1)
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="ngenius">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="ngenius" class="online_payment" checked>
                                                            <span>
                                                                <img loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/ngenius.png') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (\App\Addon::where('unique_identifier', 'african_pg')->first() != null &&
                                                    \App\Addon::where('unique_identifier', 'african_pg')->first()->activated)
                                                    @if (\App\BusinessSetting::where('type', 'mpesa')->first()->value == 1)
                                                        <div class="col-6">
                                                            <label class="payment_option mb-4" data-toggle="tooltip"
                                                                data-title="mpesa">
                                                                <input type="radio" id=""
                                                                    name="payment_option" value="mpesa"
                                                                    class="online_payment" checked>
                                                                <span>
                                                                    <img loading="lazy"
                                                                        src="{{ my_asset('frontend/images/icons/cards/mpesa.png') }}"
                                                                        class="img-fluid">
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    @if (\App\BusinessSetting::where('type', 'flutterwave')->first()->value == 1)
                                                        <div class="col-6">
                                                            <label class="payment_option mb-4" data-toggle="tooltip"
                                                                data-title="flutterwave">
                                                                <input type="radio" id=""
                                                                    name="payment_option" value="flutterwave"
                                                                    class="online_payment" checked>
                                                                <span>
                                                                    <img loading="lazy"
                                                                        src="{{ my_asset('frontend/images/icons/cards/flutterwave.png') }}"
                                                                        class="img-fluid">
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if (\App\Addon::where('unique_identifier', 'paytm')->first() != null &&
                                                    \App\Addon::where('unique_identifier', 'paytm')->first()->activated)
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip"
                                                            data-title="Paytm">
                                                            <input type="radio" id="" name="payment_option"
                                                                value="paytm" class="online_payment" checked>
                                                            <span>
                                                                <img loading="lazy"
                                                                    src="{{ my_asset('frontend/images/icons/cards/paytm.jpg') }}"
                                                                    class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                                @if (\App\BusinessSetting::where('type', 'cash_payment')->first()->value == 1)
                                                    @php
                                                        $digital = 0;
                                                        foreach (Session::get('cart') as $cartItem) {
                                                            if ($cartItem['digital'] == 1) {
                                                                $digital = 1;
                                                            }
                                                        }
                                                    @endphp
                                                    @if ($digital != 1)
                                                        <div class="col-6">
                                                            <label class="payment_option mb-4" data-toggle="tooltip"
                                                                data-title="{{ __('cash_on_delivery') }}">
                                                                <input type="radio" id=""
                                                                    name="payment_option" value="cash_on_delivery"
                                                                    class="online_payment" checked>
                                                                <span>
                                                                    <img loading="lazy"
                                                                        src="{{ my_asset('frontend/images/icons/cards/cod.png') }}"
                                                                        class="img-fluid">
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if (Auth::check())
                                                    @if (\App\Addon::where('unique_identifier', 'offline_payment')->first() != null &&
                                                        \App\Addon::where('unique_identifier', 'offline_payment')->first()->activated)
                                                        @foreach (\App\ManualPaymentMethod::all() as $method)
                                                            <div class="col-6">
                                                                <label class="payment_option mb-4" data-toggle="tooltip"
                                                                    data-title="{{ $method->heading }}">
                                                                    <input type="radio" id=""
                                                                        name="payment_option"
                                                                        value="{{ $method->heading }}"
                                                                        onchange="toggleManualPaymentData({{ $method->id }})">
                                                                    <span>
                                                                        <img loading="lazy"
                                                                            src="{{ uploaded_asset($method->photo) }}"
                                                                            class="img-fluid">
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        @endforeach

                                                        @foreach (\App\ManualPaymentMethod::all() as $method)
                                                            <div id="manual_payment_info_{{ $method->id }}"
                                                                class="d-none">
                                                                @php echo $method->description @endphp
                                                                @if ($method->bank_info != null)
                                                                    <ul>
                                                                        @foreach (json_decode($method->bank_info) as $key => $info)
                                                                            <li>Bank Name - {{ $info->bank_name }}, Account
                                                                                Name - {{ $info->account_name }}, Account
                                                                                Number - {{ $info->account_number }},
                                                                                Routing Number -
                                                                                {{ $info->routing_number }}
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-3 bg-gray text-left p-3 d-none">
                                        <div id="manual_payment_description">

                                        </div>
                                    </div>
                                    @if (Auth::check() && \App\BusinessSetting::where('type', 'wallet_system')->first()->value == 1)
                                        <div class="or or--1 mt-2">
                                            <span>or</span>
                                        </div>
                                        <div class="row">
                                            <div class="col-xxl-6 col-lg-8 col-md-10 mx-auto">
                                                <div class="text-center bg-gray py-4">
                                                    <i class="fa"></i>
                                                    @if (!Session::has('wallet_discount'))
                                                        <div class="h5 mb-4">{{ translate('Your wallet balance :') }}
                                                            <strong>{{ single_price(Auth::user()->balance) }}</strong>
                                                        </div>
                                                    @endif

                                                    @if (Session::has('wallet_discount'))
                                                        <strong>{{ translate('Discount Applied successfuly and your total payment is ') }}
                                                            {{ $total }}
                                                        </strong>
                                                    @else
                                                        @if (Auth::user()->balance < $total && Auth::user()->balance != 0)
                                                            <strong>{{ translate('Insufficient balance. Do you want to use it in this order ?') }}
                                                            </strong>
                                                            <br>
                                                            <br>
                                                            <input type="hidden" name="wallet_discount"
                                                                id="wallet_discount_input"
                                                                value="{{ Auth::user()->balance }}">
                                                            <button onclick="use_wallet_discount()" type="button"
                                                                class="btn btn-styled btn-base-1">{{ translate('yes') }}</button>
                                                        @elseif(Auth::user()->balance >= $total && Auth::user()->balance != 0)
                                                            <button type="button" onclick="use_wallet()"
                                                                class="btn btn-base-1">{{ translate('Pay with wallet') }}</button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @php
                                if (\App\Page::find(\App\Models\AppSettings::find(1)->terms_conditions)) {
                                    $tcSlug = \App\Page::find(\App\Models\AppSettings::find(1)->terms_conditions)->{'slug_' . locale()};
                                } else {
                                    $tcSlug = '';
                                }
                                if (\App\Page::find(\App\Models\AppSettings::find(1)->return_policy)) {
                                    $rpSlug = \App\Page::find(\App\Models\AppSettings::find(1)->return_policy)->{'slug_' . locale()};
                                } else {
                                    $rpSlug = '';
                                }
                                
                                if (\App\Page::find(\App\Models\AppSettings::find(1)->privacy_policy)) {
                                    $ppSlug = \App\Page::find(\App\Models\AppSettings::find(1)->privacy_policy)->{'slug_' . locale()};
                                } else {
                                    $ppSlug = '';
                                }
                                
                            @endphp
                            <div class="pt-3">
                                <input id="agree_checkbox" type="checkbox" required>
                                <label for="agree_checkbox">{{ translate('I agree to the') }}</label>
                                <a href="{{ url('/', [$tcSlug]) }}">{{ translate('terms and conditions') }}</a>,
                                <a href="{{ url('/', [$rpSlug]) }}">{{ translate('return policy') }}</a> &
                                <a href="{{ url('/', [$ppSlug]) }}">{{ translate('privacy policy') }}</a>
                            </div>

                            <div>
                                <span>برجاء التاكد من اتمام كافه البيانات بلوحه التحكم الخاصه بك</span>
                                <a href="{{ route('profile') }}" class="link link--style-3">
                                        <i class="ion-android-arrow-back"></i>
                                        {{ translate('لوحه التحكم') }}
                                </a>
                            </div>
                            
                            <div class="row align-items-center pt-3">
                                <div class="col-6">
                                    <a href="{{ route('home') }}" class="link link--style-3">
                                        <i class="ion-android-arrow-back"></i>
                                        {{ translate('Return to shop') }}
                                    </a>
                                </div>
                                <div class="col-6 text-right">
                                    <button type="button" onclick="submitOrder(this)"
                                        class="btn btn-styled btn-base-1">{{ translate('Complete Order') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-4 ml-lg-auto">
                        @include('frontend.partials.cart_summary')
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="confirm-fawry-modal" style="margin-top: 13%" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Fawry') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md text-center">
                                <script src="https://atfawry.fawrystaging.com/ECommercePlugin/scripts/V2/FawryPay.js"></script>
                                <h3>{{ translate('Click to continue') }}</h3>
                                <input type="image"
                                    onclick="FawryPay.checkout(chargeRequest,'{{ URL::to('/') }}/payment_redirect','{{ URL::to('/') }}/checkout/payment_select');"
                                    style="width: inherit"
                                    src="{{ my_asset('frontend/images/icons/cards/fawry_logo_' . locale() . '.png') }}"
                                    alt="Edfa3 Fawry" id="xsrrs" />
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function removeFromCartView(e, key) {
            e.preventDefault();
            removeFromCart(key);
        }
        $(document).ready(function() {
            $(".online_payment").click(function() {
                $('#manual_payment_description').parent().addClass('d-none');
            });
        });

        function use_wallet_discount() {
            var discount_val = $('#wallet_discount_input').val()
            $.post(`{{ route('checkout.applay_wallet_discount') }}`, {
                _token: '{{ csrf_token() }}',
                discount_val: discount_val
            }, function(data) {
                if (data == true) {
                    location.reload();


                } else {
                    alert(`{{ translate('error occured. please try again') }}`)

                }
                console.log(data);
            });
        }

        function use_wallet() {
            $('input[name=payment_option]').val('wallet');
            if ($('#agree_checkbox').is(":checked")) {
                $('#checkout-form').submit();
            } else {
                showFrontendAlert('error', `{{ translate('You need to agree with our policies') }}`);
            }
        }

        function submitOrder(el) {
            $(el).prop('disabled', true);
            if ($('#agree_checkbox').is(":checked")) {
                $('#checkout-form').submit();
            } else {
                showFrontendAlert('error', `{{ translate('You need to agree with our policies') }}`);
                $(el).prop('disabled', false);
            }
        }

        function toggleManualPaymentData(id) {
            $('#manual_payment_description').parent().removeClass('d-none');
            $('#manual_payment_description').html($('#manual_payment_info_' + id).html());
        }
    </script>

    <script>
        var fawryy = `{{ $fawry }}`;
        if (fawryy == true) {

            var perfLang = `{{ locale() }}`;
            if (perfLang == 'ar') {
                perfLang = 'ar-eg';
            } else {
                perfLang = 'en-us';
            }
            console.log(`{{ $total }}`);
            $('#confirm-fawry-modal').modal('show');
            var chargeRequest = {};
            chargeRequest.language = perfLang;
            chargeRequest.merchantCode = '{{ env('FAWRY_MERCHANTCODE') }}';
            chargeRequest.paymentMethod = 'PAYATFAWRY';
            chargeRequest.merchantRefNumber = `{{ Session::get('order_id') }}` + '_' + `{{ $total }}`;
            chargeRequest.customer = {}
            chargeRequest.customer.name = `{{ $shipping_info['name'] }}`;
            chargeRequest.customer.mobile = `{{ $shipping_info['phone'] }}`;
            chargeRequest.customer.email = `{{ $shipping_info['email'] }}`;
            chargeRequest.customer.customerProfileId = `{{ $shipping_info['email'] }}`;
            chargeRequest.order = {};
            chargeRequest.order.description = 'pay order';
            chargeRequest.order.expiry = '';
            chargeRequest.order.orderItems = [];
            var itemPrices = 0;
            $.post(`{{ route('checkout.getCartItems') }}`, {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $.each(data, function(index, value) {
                    var item = {};
                    item.imageUrl = value.thumbnail_img;
                    item.productSKU = value.thumbnail_img;
                    item.description = value.name;
                    item.price = value.unit_price;
                    item.quantity = value.quantity;
                    item.width = '12222';
                    item.height = '12222';
                    item.length = '12222';
                    item.weight = '12222';
                    chargeRequest.order.orderItems.push(item);
                    itemPrices += value.unit_price * value.quantity;
                })
                console.log(itemPrices);
                var item = {};
                item.imageUrl = '';
                item.productSKU = 'Fees';
                item.description = 'Fees';
                item.price = parseFloat(`{{ $total }}`) - itemPrices;
                item.quantity = 1;
                item.width = '12222';
                item.height = '12222';
                item.length = '12222';
                item.weight = '12222';
                chargeRequest.order.orderItems.push(item);

                console.log(chargeRequest.order.orderItems);

            });


            chargeRequest.signature = '{{ env('FAWRY_SIGNATURE') }}';



            function requestCanceldCallBack(merchantRefNum) {
                // Your implementation to handle the calncel button				 
            }

            function fawryCallbackFunction(paid, billingAcctNum, paymentAuthId, merchantRefNum, messageSignature) {
                // Your implementation
            }
        }
    </script>
    <script src="https://grey.paysky.io:9006/invchost/JS/LightBox.js"></script>
    <script type="text/javascript">
        var paysky = `{{ $paysky }}`;
        if (paysky == true) {
            callLightbox();

            function callLightbox() {

                const start = Date.now();
                Lightbox.Checkout.configure = {
                    MID: '{{ env('PAYSKY_MID') }}',
                    TID: '{{ env('PAYSKY_TID') }}',
                    AmountTrxn: `{{ $total }}` * 100,
                    MerchantReference: `{{ Session::get('order_id') }}` + '_' + start,
                    TrxDateTime: '202009171418',
                    SecureHash: '{{ env('PAYSKY_HASH') }}',
                    completeCallback: function(data) {
                        console.log('completed');
                        setTimeout(() => {
                            window.location.replace(
                                `{{ URL::to('/') }}/paysky_redirect?MerchantReference=${data.MerchantReference}&SystemReference=${data.SystemReference}`
                            );

                        }, 3000);
                        console.log(data);
                    },
                    errorCallback: function(data) {
                        console.log('error');
                        console.log(data);
                    },
                    cancelCallback: function() {
                        console.log('cancel');
                    }
                };

                Lightbox.Checkout.showLightbox();
            }
        }
    </script>
@endsection
