@extends('frontend.layouts.empty')
<script src="https://atfawry.fawrystaging.com/ECommercePlugin/scripts/V2/FawryPay.js">
</script>
@php
if($order['lang'] == 'ar'){
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
                onclick="FawryPay.checkout(chargeRequest,'{{URL::to('/')}}/fawry_sucsess','{{URL::to('/')}}/fawry_sucsess');"
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
        chargeRequest.merchantRefNumber = `{{ $order['order_id'] }}` + '_' + `{{ $order['total_money'] }}`;
        chargeRequest.customer = {}
        chargeRequest.customer.name = `{{ $order['costomer_name'] }}`;
        chargeRequest.customer.mobile = `{{ $order['customer_phone'] }}`;
        chargeRequest.customer.email = `{{ $order['customer_email'] }}`;
        chargeRequest.customer.customerProfileId = `{{ $order['customer_email'] }}`;
        chargeRequest.order = {};
        chargeRequest.order.description = 'pay order';
        chargeRequest.order.expiry = '';
        chargeRequest.order.orderItems = [];
        var itemPrices = 0;
        console.log('ssss' + `{{ $order['order_id'] }}`);
        $.post(`{{ route('checkout.getOrderItems') }}`, {
            _token: '{{ csrf_token() }}',
            order_id: `{{ $order['order_id'] }}`
        }, function(data) {
            console.log(data);
           
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
            item.price = parseFloat(`{{ $order['total_money'] }}`) - itemPrices;
            item.quantity = 1;
            item.width = '12222';
            item.height = '12222';
            item.length = '12222';
            item.weight = '12222';
            chargeRequest.order.orderItems.push(item);

            console.log(chargeRequest.order.orderItems);

        });
        chargeRequest.signature = '{{  env('FAWRY_SIGNATURE') }}';



        function requestCanceldCallBack(merchantRefNum) {
            // Your implementation to handle the calncel button				 
        }

        function fawryCallbackFunction(paid, billingAcctNum, paymentAuthId, merchantRefNum, messageSignature) {
            // Your implementation
        }

    </script>
@endsection
