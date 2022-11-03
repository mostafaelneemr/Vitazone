@extends('frontend.layouts.empty')
<script src="https://atfawry.fawrystaging.com/ECommercePlugin/scripts/V2/FawryPay.js">
</script>
<section class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center py-4 border-bottom mb-4">
                            <i class="la la-check-circle la-3x text-success mb-3"></i>
                            <h1 class="h3 mb-3">{{ translate('Thank You for Your Order!') }}</h1>
                            <h2 class="h5 strong-700">{{ translate('Order Code:') }} {{ $order->code }}</h2>
                            <p class="text-muted text-italic">
                                {{ translate('A copy or your order summary has been sent to') }}
                                {{ json_decode($order->shipping_address)->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('script')

    <script>
        $(document).ready(function() {
            setTimeout(() => {
                window.history.back();
            }, 4000);
        })

    </script>

@endsection
