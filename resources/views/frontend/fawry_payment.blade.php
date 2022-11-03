@extends('frontend.layouts.app')

@section('content')
    <script src="https://atfawry.fawrystaging.com/ECommercePlugin/scripts/V2/FawryPay.js"></script>
    <h3>أضغط للأستمرار</h3>
    <input type="image" onclick="FawryPay.checkout(chargeRequest,'{{URL::to('/')}}/payment_redirect','{{URL::to('/')}}');" src="https://www.atfawry.com/ECommercePlugin/resources/images/atfawry-ar-logo.png"
        alt="Edfa3 Fawry" id="xsrrs" />
    <script>
        var xx = Math.floor(Math.random() * 100000) + 1000000;
        var chargeRequest = {};
        chargeRequest.language = 'eg-ar';
        chargeRequest.merchantCode = '{{  env('FAWRY_MERCHANTCODE') }}';
        chargeRequest.paymentMethod = 'PAYATFAWRY';
        chargeRequest.merchantRefNumber = xx;
        chargeRequest.customer = {}
        chargeRequest.customer.name = 'asd';
        chargeRequest.customer.mobile = '';
        chargeRequest.customer.email = '';
        chargeRequest.customer.customerProfileId = 'asd@sss.com';
        chargeRequest.order = {};
        chargeRequest.order.description = 'test bill inq';
        chargeRequest.order.expiry = '';
        chargeRequest.order.orderItems = [];
        var item = {};
        item.productSKU = '12222';
        item.description = '12222';
        item.price = '30';
        item.quantity = '2';
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