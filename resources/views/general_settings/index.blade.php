@extends('layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('General Settings') }}</h1>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6 col-lg-offset-3">
            <div class="card">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="form-horizontal" action="{{ route('generalsettings.update', $generalsetting->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Site Name English') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="site_name_en" name="site_name_en"
                                    placeholder="{{ translate('Site Name English') }}"
                                    value="{{ $generalsetting->site_name_en }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="name">{{ translate('Site Name Arabic') }}</label>
                            <div class="col-md-8">
                                <input type="text" id="site_name_ar" name="site_name_ar"
                                    value="{{ $generalsetting->site_name_ar }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label"
                                for="address">{{ translate('Address English') }}</label>
                            <div class="col-md-8">
                                <input type="text" id="address_en" name="address_en"
                                    value="{{ $generalsetting->address_en }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label"
                                for="address">{{ translate('Address Arabic') }}</label>
                            <div class="col-md-8">
                                <input type="text" id="address_ar" name="address_ar"
                                    value="{{ $generalsetting->address_ar }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label"
                                for="name">{{ translate('Footer Text English') }}</label>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="4" name="description_en"
                                    required>{{ $generalsetting->description_en }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label"
                                for="name">{{ translate('Footer Text Arabic') }}</label>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="4" name="description_ar"
                                    required>{{ $generalsetting->description_ar }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="phone">{{ translate('Phone') }}</label>
                            <div class="col-md-8">
                                <input type="text" id="phone" name="phone" value="{{ $generalsetting->phone }}"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="email">{{ translate('Email') }}</label>
                            <div class="col-md-8">
                                <input type="text" id="email" name="email" value="{{ $generalsetting->email }}"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="facebook">{{ translate('Facebook') }}</label>
                            <div class="col-md-8">
                                <input type="text" id="facebook" name="facebook" value="{{ $generalsetting->facebook }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="instagram">{{ translate('Instagram') }}</label>
                            <div class="col-md-8">
                                <input type="text" id="instagram" name="instagram"
                                    value="{{ $generalsetting->instagram }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="twitter">{{ translate('Twitter') }}</label>
                            <div class="col-md-8">
                                <input type="text" id="twitter" name="twitter" value="{{ $generalsetting->twitter }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="youtube">{{ translate('Youtube') }}</label>
                            <div class="col-md-8">
                                <input type="text" id="youtube" name="youtube" value="{{ $generalsetting->youtube }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label"
                                for="google_plus">{{ translate('Google Plus') }}</label>
                            <div class="col-md-8">
                                <input type="text" id="google_plus" name="google_plus"
                                    value="{{ $generalsetting->google_plus }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('System Timezone') }}</label>
                            <div class="col-md-8">
                                <select name="timezone" class="form-control demo-select2" data-live-search="true">
                                    @foreach (timezones() as $key => $value)
                                        <option value="{{ $value }}" @if (app_timezone() == $value) selected @endif>
                                            {{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label"
                                for="address">{{ translate('Invoice Instructions English') }}</label>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="4" name="invoice_instructions_en"
                                    required>{{ $generalsetting->invoice_instructions_en }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label"
                                for="address">{{ translate('Invoice Instructions Arabic') }}</label>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="4" name="invoice_instructions_ar"
                                    required>{{ $generalsetting->invoice_instructions_ar }}</textarea>
                            </div>
                        </div>
                        <div class="mb-3 text-right">
                            <button type="submit" name="button" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>

                </form>
                <!--===================================================-->
                <!--End Horizontal Form-->

            </div>
        </div>
        <div class="col-lg-3"></div>
    </div>


@endsection
