@extends('frontend.layouts.empty')
<script src="https://atfawry.fawrystaging.com/ECommercePlugin/scripts/V2/FawryPay.js">
</script>
@php
if($wallet['lang'] == 'ar'){
$cl_to_cont = 'أضغط للأستمرار .';
$langg = 'ar-eg';
$langgg = 'ar';
}else {
$cl_to_cont = 'Click to continue';
$langg = 'en-us';
$langgg = 'en';
}
@endphp
<div class="container">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md text-center">
            <script src="https://atfawry.fawrystaging.com/ECommercePlugin/scripts/V2/FawryPay.js">
            </script>
            <br />
            <h4>{{ $cl_to_cont }}</h4>
            <input type="image"
                onclick="FawryPay.checkout(chargeRequest,'{{ route('wallet_payment_fawry_done_app') }}','{{ route('wallet_payment_fawry_faile_app') }}');"
                style="width: inherit"
                src="{{ my_asset('frontend/images/icons/cards/fawry_logo_' . $langgg . '.png') }}" alt="Edfa3 Fawry"
                id="xsrrs" />
        </div>
        <div class="col-md-2"></div>
    </div>
</div>

@section('script')
    <script>
        perfLang = `{{ $langg }}`;
        $('#confirm-fawry-modal').modal('show');
        var chargeRequest = {};
        chargeRequest.language = perfLang;
        chargeRequest.merchantCode = '{{  env('FAWRY_MERCHANTCODE') }}';
        chargeRequest.paymentMethod = 'PAYATFAWRY';
        chargeRequest.merchantRefNumber = `{{ $wallet['user_id'] }}` + '-' + `{{ $wallet['amount'] }}` + '-' +
            `{{ $langgg }}` + '-' + Math.floor(Math.random() * 10000);
        chargeRequest.customer = {}
        chargeRequest.customer.name = `{{ $wallet['name'] }}`;
        chargeRequest.customer.mobile = `{{ $wallet['phone'] }}`;
        chargeRequest.customer.email = `{{ $wallet['email'] }}`;
        chargeRequest.customer.customerProfileId = `{{ $wallet['email'] }}`;
        chargeRequest.order = {};
        chargeRequest.order.description = 'pay order';
        chargeRequest.order.expiry = '';
        chargeRequest.order.orderItems = [];
        var item = {};
        item.productSKU = 'charge wallet';
        item.description = 'charge wallet';
        item.price = `{{ $wallet['amount'] }}`;
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

    </script>
@endsection
