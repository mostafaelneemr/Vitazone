@extends('frontend.layouts.app')

@section('content')
    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @include('frontend.inc.customer_side_nav')
                </div>
                <div class="col-lg-9">
                    <div class="page-title">
                        <div class="row align-items-center">
                            <div class="col-md-6 col-12">
                                <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                    {{ translate('Affiliate') }}
                                </h2>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Payment Settings') }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('affiliate.payment_settings_store') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">{{ translate('Paypal Email') }}</label>
                                    <div class="col-md-10">
                                        <input type="email" class="form-control"
                                            placeholder="{{ translate('Paypal Email') }}" name="paypal_email"
                                            value="{{ $affiliate_user->paypal_email }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">{{ translate('Bank Informations') }}</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control"
                                            placeholder="{{ translate('Acc. No, Bank Name etc') }}"
                                            name="bank_information" value="{{ $affiliate_user->bank_information }}">
                                    </div>
                                </div>
                                <div class="form-group mb-0 text-right">
                                    <button type="submit"
                                        class="btn btn-primary">{{ translate('Update Payment Settings') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>




            </div>
        </div>
    </section>
@endsection
