@extends('frontend.layouts.empty')
@section('script')
    <script src="https://grey.paysky.io:9006/invchost/JS/LightBox.js"></script>
    <script type="text/javascript">
        callLightbox();

        function callLightbox() {
            const start = Date.now();
            Lightbox.Checkout.configure = {
                MID: '{{ env('PAYSKY_MID') }}',
                TID: '{{ env('PAYSKY_TID') }}',
                AmountTrxn: `{{ $wallet['total_money'] }}` * 100,
                MerchantReference: `{{ $wallet['user_id'] }}` + '_' + start,
                TrxDateTime: start,
                SecureHash: '{{  env('PAYSKY_HASH') }}',
                completeCallback: function(data) {
                    $.post(`{{ route('wallet_paysky_sucsess') }}`, {
                        _token: '{{ csrf_token() }}',
                        user_id: `{{ $wallet['user_id'] }}`,
                        amount: `{{ $wallet['total_money'] }}`
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
