@extends('layouts.app')

@section('content')

    <!-- Basic Data Tables -->
    <!--===================================================-->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Refund Request All') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Order Id') }}</th>
                        <th>{{ __('Seller Name') }}</th>
                        <th>{{ __('Product') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Seller Approval') }}</th>
                        <th>{{ __('Refund Status') }}</th>
                        <th width="10%">{{ __('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($refunds as $key => $refund)
                        <tr>
                            <td>{{ $key + 1 + ($refunds->currentPage() - 1) * $refunds->perPage() }}</td>
                            <td>
                                @if ($refund->order != null)
                                    {{ $refund->order->code }}
                                @endif

                            </td>
                            <td>
                                @if ($refund->seller != null)
                                    {{ $refund->seller->name }}
                                @endif
                            </td>
                            <td>
                                @if ($refund->orderDetail != null && $refund->orderDetail->product != null)
                                    @php
                                        if (locale() == 'ar') {
                                            $rout_product = $refund->orderDetail->product->slug_ar;
                                        } else {
                                            $rout_product = $refund->orderDetail->product->slug_en;
                                        }
                                    @endphp
                                    <a href="{{ route('product', $rout_product) }}" target="_blank" class="media-block">
                                        <div class="form-group row">
                                            <div class="col-md-5">
                                                <img src="{{ uploaded_asset($refund->orderDetail->product->thumbnail_img) }}"
                                                    alt="Image" class="w-50px">
                                            </div>
                                            <div class="col-md-7">
                                                <div class="media-body">
                                                    {{ __($refund->orderDetail->product->name) }}</div>
                                            </div>
                                        </div>
                                    </a>

                                @endif
                            </td>
                            <td>
                                @if ($refund->orderDetail != null)
                                    {{ single_price($refund->refund_amount) }}
                                @endif
                            </td>
                            <td>
                                @if ($refund->orderDetail != null && $refund->orderDetail->product != null && $refund->orderDetail->product->added_by == 'admin')
                                    <span class="badge badge-inline badge-warning">{{ translate('Own Product') }}</span>
                                @else
                                    @if ($refund->seller_approval == 1)
                                        <span class="badge badge-inline badge-success">{{ translate('Approved') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-primary">{{ translate('Pending') }}</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if ($refund->refund_status == 1)
                                    <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                @else
                                    <span class="badge badge-inline badge-warning">{{ translate('Non-Paid') }}</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{ route('refund_request_money_by_admin', ['id' => $refund->id]) }}"
                                    title="{{ translate('Refund Now') }}">
                                    <i class="las la-backward"></i>
                                </a>


                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="clearfix">
                <div class="pull-right">
                    {{ $refunds->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript">
        function update_refund_approval(el) {
            $.post(`{{ route('vendor_refund_approval') }}`, {
                    _token: '{{ @csrf_token() }}',
                    el: el
                },
                function(data) {
                    console.log(data)
                    if (data == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Approval has been done successfully') }}');
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    }
                });
        }

        // function refund_request_money(el) {
        //     $.post(`{{ route('refund_request_money_by_admin') }}`, {
        //             _token: '{{ @csrf_token() }}',
        //             el: el
        //         },
        //         function(data) {
        //             if (data == 1) {
        //                 location.reload();
        //                 showAlert('success', 'Refund has been sent successfully');
        //             } else {
        //                 showAlert('danger', 'Something went wrong');
        //             }
        //         });
        // }

    </script>
@endsection
