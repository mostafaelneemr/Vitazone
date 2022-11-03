@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Take refund Action') }}</h5>
                </div>
                <div class="card-body">
                    <form class="" action="{{ route('getRefunded') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-box bg-white mt-4">
                            <div class="form-box-content p-3">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>{{ __('Reason') }} <span class="required-star"></span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control mb-3" name="reason"
                                            placeholder="{{ __('Reason') }}" value="{{ $refund->reason }}" readonly>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>{{ __('Admin Reason') }} <span class="required-star"></span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control mb-3" name="admin_reason"
                                            placeholder="{{ __('Admin Reason') }}"
                                            value="{{ $refund->refundResone ? $refund->refundResone->{'resone_' . locale()} : '' }}"
                                            readonly>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>{{ __('Amount') }} <span class="required-star"></span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="number" class="form-control mb-3" name="amount"
                                            placeholder="{{ __('Amount') }}" value="{{ $refund->refund_amount }}"
                                            readonly>
                                    </div>
                                </div>

                                <br>
                                <input type="hidden" name="id" value="{{ $refund->id }}">
                                <input type="hidden" name="order_id" value="{{ $refund->order->id }}">
                                <input type="hidden" name="coupon_discount"
                                    value="{{ $refund->order->coupon_discount }}">
                                <input type="hidden" name="shipping_cost"
                                    value="{{ $refund->orderDetail->shipping_cost }}">
                                <input type="hidden" name="seller_approval" value="{{ $refund->seller_approval }}">
                                <input type="hidden" name="seller_id" value="{{ $refund->seller_id }}">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>{{ __('Payment method') }} <span class="required-star"></span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control mb-3" name="payment_type"
                                            placeholder="{{ __('Amount') }}"
                                            value="{{ __($refund->order->payment_type) }}" readonly>
                                    </div>
                                </div>

                                <br>
                                <div class="form-group row">
                                    <label
                                        class="col-lg-3 control-label">{{ translate('Shipping cost discount') }}</label>
                                    <div class="col-lg-7">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="withshipment" checked>
                                            <span class="slider round"></span></label>
                                        </label>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
