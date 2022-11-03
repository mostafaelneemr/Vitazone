@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Google Analytics Setting') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('google_analytics.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{ translate('Google Analytics') }}</label>
                            </div>
                            <div class="col-md-7">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="google_analytics" type="checkbox" @if (\App\BusinessSetting::where('type', 'google_analytics')->first()->value == 1) checked @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="TRACKING_ID">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{ translate('Tracking ID') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="TRACKING_ID"
                                    value="{{ env('TRACKING_ID') }}" placeholder="{{ translate('Tracking ID') }}"
                                    required>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
@endsection
