@extends('frontend.layouts.empty')
@section('script')
    <script src="https://cube.paysky.io:6006/js/LightBox.js"></script>
    <script type="text/javascript">
        callLightbox();

        function callLightbox() {
            const start = Date.now();
            Lightbox.Checkout.configure = {
                MID: '{{  env('PAYSKY_MID') }}',
                TID: '{{  env('PAYSKY_TID') }}',
                AmountTrxn: `{{ $order['total_money'] }}` * 100,
                MerchantReference: `{{ $order['order_id'] }}` + '_' + start,
                TrxDateTime: start,
                SecureHash: '{{  env('PAYSKY_HASH') }}',
                completeCallback: function(data) {
                    $.post(`{{ route('paysky_sucsess') }}`, {
                        _token: '{{ csrf_token() }}',
                        order_id: `{{ $order['order_id'] }}`,
                        MerchantReference: data.MerchantReference,
                        SystemReference: data.SystemReference
                    }, function(data2) {

                        
                    });
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

    </script>
@endsection
