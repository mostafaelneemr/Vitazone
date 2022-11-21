@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-12 mx-auto">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{ translate('Track Order') }}
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <form class="" action="{{ route('orders.track') }}" method="GET" enctype="multipart/form-data">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Order Info') }}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Order Code') }} <span
                                                    class="required-star">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3"
                                                placeholder="{{ translate('Order Code') }}" name="order_code" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit"
                                    class="btn btn-styled btn-base-1">{{ translate('Track Order') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @isset($order)
                <div class="card mt-4">
                    <div class="card-header py-2 px-3 heading-6 strong-600 clearfix">
                        <div class="float-left">{{ translate('Order Summary') }}</div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-6">
                                <table class="details-table table">
                                    <tr>
                                        <td class="w-50 strong-600">{{ translate('Order Code') }}:</td>
                                        <td>{{ $order->code }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 strong-600">{{ translate('Customer') }}:</td>
                                        <td>{{ json_decode($order->shipping_address)->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 strong-600">{{ translate('Email') }}:</td>
                                        @if ($order->user_id != null)
                                            <td>{{ $order->user->email }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td class="w-50 strong-600">{{ translate('Shipping address') }}:</td>
                                        <td>{{ json_decode($order->shipping_address)->address }},
                                            {{ \App\Region::where('id', json_decode($order->shipping_address)->region)->value('name_' . locale()) }}
                                            ,{{ \App\City::where('id', json_decode($order->shipping_address)->city)->value('name_' . locale()) }},
                                            {{ \App\Country::where('id', json_decode($order->shipping_address)->country)->value('name_' . locale()) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-6">
                                <table class="details-table table">
                                    <tr>
                                        <td class="w-50 strong-600">{{ translate('Order date') }}:</td>
                                        <td>{{ date('d-m-Y H:i A', $order->date) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 strong-600">{{ translate('Shipping date') }}:</td>
                                        <td>{{ json_decode($order->shipping_address)->shipping_date }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 strong-600">{{ translate('Shipping') }}:</td>
                                        <td>{{ single_price(json_decode($order->shipping_address)->shipping_cost) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 strong-600">{{ translate('Coupon Discount') }}</td>
                                        <td>
                                            <span class="text-italic">{{ single_price($order->coupon_discount) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 strong-600">{{ translate('Wallet discount') }}</td>
                                        <td>
                                            <span class="text-italic">{{ single_price($order->wallet_discount) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 strong-600">{{ translate('Total order amount') }}:</td>
                                        <td>{{ single_price($order->grand_total) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 strong-600">{{ translate('Payment method') }}:</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', __($order->payment_type))) }}</td>
                                    </tr>
                                    @if ($order->payment_type == 'cash_on_delivery' && $order->payment_status == 'unpaid' && $order->orderDetails[0]->delivery_status != 'delivered')
                                        <hr>
                                        <tr>
                                            @if (\App\BusinessSetting::where('type', 'paysky')->first()->value == 1)
                                                <td>
                                                    <a href="{{ route('orders.track', ['order_code' => $_GET['order_code'], 'paysky' => true]) }}"
                                                        class="btn btn-styled btn-base-1">{{ translate('Pay with paysky') }}</a>
                                                </td>
                                            @endif
                                            @if (\App\BusinessSetting::where('type', 'fawry')->first()->value == 1)
                                                <td>
                                                    <a href="{{ route('orders.track', ['order_code' => $_GET['order_code'], 'fawry' => true]) }}"
                                                        class="btn btn-styled btn-base-1">{{ translate('Pay with fawry') }}</a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                @foreach ($order->orderDetails as $key => $orderDetail)
                    @php
                    $status = $orderDetail->delivery_status;
                    @endphp
                    <div class="card mt-4">
                        <div class="card-header py-2 px-3 heading-6 strong-600 clearfix">
                            <ul class="process-steps clearfix">
                            <li @if ($status == 'pending') class="active" @else
                                    class="done"
                @endif>
                <div class="icon">{{ translate('1') }}</div>
                <div class="title">{{ translate('Order placed') }}</div>
                </li>
            <li @if ($status == 'on_review') class="active" @elseif($status ==
                    'on_delivery' || $status == 'delivered') class="done" @endif>
                    <div class="icon">{{ translate('2') }}</div>
                    <div class="title">{{ translate('On review') }}</div>
                </li>
            <li @if ($status == 'on_delivery') class="active" @elseif($status ==
                    'delivered') class="done" @endif>
                    <div class="icon">{{ translate('3') }}</div>
                    <div class="title">{{ translate('On delivery') }}</div>
                </li>
                <li @if ($status == 'delivered') class="done" @endif>
                    <div class="icon">{{ translate('4') }}</div>
                    <div class="title">{{ translate('Delivered') }}</div>
                </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="col-6">
                    <table class="details-table table">
                        @if ($orderDetail->product != null)
                            <tr>
                                <td class="w-50 strong-600">{{ translate('Product Name') }}:</td>
                                <td>{{ $orderDetail->product->{'name_' . locale()} }} ({{ $orderDetail->variation }})</td>
                            </tr>
                            <tr>
                                <td class="w-50 strong-600">{{ translate('Quantity') }}:</td>
                                <td>{{ $orderDetail->quantity }}</td>
                            </tr>
                            <tr>
                                <td class="w-50 strong-600">{{ translate('Shipped By') }}:</td>
                                <td>{{ $orderDetail->product->user->name }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
            </div>
            @endforeach

        @endisset
        </div>
    </section>

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
                                <script src="https://atfawry.fawrystaging.com/ECommercePlugin/scripts/V2/FawryPay.js">
                                </script>
                                <h3>{{ translate('Click to continue') }}</h3>
                                <input type="image"
                                    onclick="FawryPay.checkout(chargeRequest,'{{ route('orders.repay_ordre_fawry_done') }}','{{ route('orders.repay_ordre_faile') }}');"
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
@isset($order)
@section('script')

    <script>
        var fawryy = `{{ $fawry }}`;
        if (fawryy == true) {
            const start1 = Date.now();
            var perfLang = `{{ locale() }}`;
            if (perfLang == 'ar') {
                perfLang = 'ar-eg';
            } else {
                perfLang = 'en-us';
            }
            $('#confirm-fawry-modal').modal('show');
            var chargeRequest = {};
            chargeRequest.language = perfLang;
            chargeRequest.merchantCode = '{{  env('FAWRY_MERCHANTCODE') }}';
            chargeRequest.paymentMethod = 'PAYATFAWRY';
            chargeRequest.merchantRefNumber = `{{  $order->code }}` + '-' + start1;
            chargeRequest.customer = {}
            chargeRequest.customer.name = `{{ $shipping_address['name'] }}`;
            chargeRequest.customer.mobile = `{{ $shipping_address['phone'] }}`;
            chargeRequest.customer.email = `{{ $shipping_address['email'] }}`;
            chargeRequest.customer.customerProfileId = `{{ $shipping_address['email'] }}`;
            chargeRequest.order = {};
            chargeRequest.order.description = 'pay order';
            chargeRequest.order.expiry = '';
            chargeRequest.order.orderItems = [];
            var item = {};
            item.productSKU = '12222';
            item.description = '12222';
            item.price = `{{ $order->grand_total }}`;
            item.quantity = '1';
            item.width = '12222';
            item.height = '12222';
            item.length = '12222';
            item.weight = '12222';
            chargeRequest.order.orderItems.push(item);
            chargeRequest.signature = '{{  env('FAWRY_SIGNATURE') }}';



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
                    MID: '{{  env('PAYSKY_MID') }}',
                    TID: '{{  env('PAYSKY_TID') }}',
                    AmountTrxn: `{{ $order->grand_total }}` * 100,
                    MerchantReference: `{{ $order->id }}` + '_' + start,
                    TrxDateTime: start,
                    SecureHash: '{{  env('PAYSKY_HASH') }}',
                    completeCallback: function(data) {
                        console.log('completed');
                        setTimeout(() => {
                            window.location.replace(
                                `{{ route('orders.repay_ordre_paysky_done', ['order_code' => $_GET['order_code']]) }}`
                            );

                        }, 2000);
                        console.log(data);
                    },
                    errorCallback: function(data) {
                        setTimeout(() => {
                            window.location.replace(
                                `{{ route('orders.repay_ordre_faile', ['order_code' => $_GET['order_code']]) }}`
                            );

                        }, 2000);
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

@endisset
