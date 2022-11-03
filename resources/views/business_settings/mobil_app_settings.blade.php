@extends('layouts.app')

@section('content')

    <div class="row">

        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 ">{{ translate('Mobile app settings') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('mobil_app_settings.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">{{ translate('Enable / Disable mobile app') }}</div>
                            <div class="col-md-4">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="mobile_app" type="checkbox" @if (\App\BusinessSetting::where('type', 'mobile_app')->first()->value == 1)
                                    checked
                                    @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="mobile_app_firebase_token">
                            <div class="col-md-4">
                                <label class="col-from-label">{{ translate('Firebase token') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="mobile_app_firebase_token"
                                    value="{{ \App\BusinessSetting::where('type', 'mobile_app_firebase_token')->first()->value }}"
                                    placeholder="{{ translate('Firebase token') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="mobile_app_googleplay_link">
                            <div class="col-md-4">
                                <label class="col-from-label">{{ translate('Google play link') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="mobile_app_googleplay_link"
                                    value="{{ \App\BusinessSetting::where('type', 'mobile_app_googleplay_link')->first()->value }}"
                                    placeholder="{{ translate('Google play link') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="mobile_app_appstore_link">
                            <div class="col-md-4">
                                <label class="col-from-label">{{ translate('App store link') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="mobile_app_appstore_link"
                                    value="{{ \App\BusinessSetting::where('type', 'mobile_app_appstore_link')->first()->value }}"
                                    placeholder="{{ translate('App store link') }}">
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
