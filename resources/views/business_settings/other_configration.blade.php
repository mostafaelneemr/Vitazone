@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 ">{{ translate('Tawk Chat') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('other_configration.update') }}" method="POST">
                        <input type="hidden" name="payment_method" value="paypal">
                        @csrf
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="TAWK_ID">
                            <div class="col-md-4">
                                <label class="col-from-label">{{ translate('TAWK ID') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="TAWK_ID"
                                    value="{{ env('TAWK_ID') }}"
                                    placeholder="{{ translate('Paypal Client ID') }}" required>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>

@endsection
