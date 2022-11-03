@extends('layouts.app')

@section('content')

    <div class="col-lg-7 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Role Information')}}</h5>
            </div>

            <!--Horizontal Form-->
            <!--===================================================-->
            <form action="{{ route('roles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label" for="name">{{translate('Name')}}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="card-header">
                        <h3 class="mb-0 h6">{{ translate('Permissions') }}</h3>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-from-label" for="banner"></label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Products') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="1" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Tags') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="14">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Brands') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="28">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Category') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="18">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Flash Deal') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="2">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Orders') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="3">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Sales') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="4">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Sellers') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="5">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Customers') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="6">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Conversation') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="16">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Reports') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="17">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Messaging') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="7">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Business Settings') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="8">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Frontend Settings') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="9">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Staffs') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="10">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('SEO Setting') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="11">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('E-commerce Setup') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="12">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Support Ticket') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="13">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Addon Manager') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="15">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Blog') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="19">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Activity Log') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="20">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Refund') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="21">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Files') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="22">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Offline Payment System') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="23">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Club Point') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="24">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Otp') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="25">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Model Setting') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="26">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Affiliate') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="27">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                    </div>
                </div>
                
            </form>
            <!--===================================================-->
            <!--End Horizontal Form-->

        </div>
    </div>

@endsection
