@extends('layouts.app')
@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('Update Package Information') }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body p-0">
                    <form class="p-4" action="{{ route('customer_packages.update', $customer_package->id) }}"
                        method="POST">
                        <input type="hidden" name="_method" value="PATCH">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Package Name Arabic') }}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{ translate('Package Name Arabic') }}" id="name_ar"
                                    name="name_ar" value="{{ $customer_package->name_ar }}" class="form-control"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Package Name English') }}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{ translate('Package Name English') }}" id="name_en"
                                    name="name_en" value="{{ $customer_package->name_en }}" class="form-control"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Amount') }}</label>
                            <div class="col-sm-9">
                                <input type="number" lang="en" min="0" step="0.01" placeholder="{{ translate('Amount') }}"
                                    value="{{ $customer_package->amount }}" id="amount" name="amount"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Product Upload') }}</label>
                            <div class="col-sm-9">
                                <input type="number" lang="en" min="0" step="1"
                                    placeholder="{{ translate('Product Upload') }}"
                                    value="{{ $customer_package->product_upload }}" id="product_upload"
                                    name="product_upload" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="signinSrEmail">{{ translate('Package Logo English') }}</label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="logo_en" value="{{ $customer_package->logo_en }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="signinSrEmail">{{ translate('Package Logo Arabic') }}</label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="logo_ar" value="{{ $customer_package->logo_ar }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group mr-2" role="group" aria-label="First group">
                                    <button type="submit" name="button" value="save" class="btn btn-warning">{{ translate('Save') }}</button>
                                </div>
                                <div class="btn-group mr-2" role="group" aria-label="Third group">
                                    <button type="submit" name="button" value="update" class="btn btn-primary">{{ translate('Update') }}</button>
                                </div>
                               
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
