@extends('frontend.layouts.app')

@section('content')
    <style>
        .coupon-cont {
            display: none;
        }
    </style>
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
                        <div class="card">
                            <div class="card-title px-4 py-3">
                                <h3 class="heading heading-5 strong-500">
                                    {{ translate('Pay with VALU') }}
                                </h3>
                            </div>
                            @if ($invalidData == true)
                                <h3 class="alert alert-danger text-center">
                                    {{ translate('Invalid Mobile number! Plese try again') }}</h3>
                            @endif
                            <div class="card-body text-center">
                                <iframe style="width: 100%;height: 70em;" src="{{ $iframe }}"
                                    title="description"></iframe>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 ml-lg-auto">
                        @include('frontend.partials.cart_summary')
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script type="text/javascript"></script>
@endsection
