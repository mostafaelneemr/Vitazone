@extends('layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Role Information') }}</h5>
    </div>

    <div class="col-lg-7 mx-auto">
        <div class="card">
            <form class="p-4" action="{{ route('roles.update', $role->id) }}" method="POST" enctype="multipart/form-data">
                <input name="_method" type="hidden" value="PATCH">
                @csrf
                <div class="card-body p-0">
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label" for="name">{{ translate('Name') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Name') }}" id="name" name="name"
                                class="form-control" value="{{ $role->name }}" required>
                        </div>
                    </div>
                    <div class="card-header">
                        <h3 class="mb-0 h6">{{ translate('Permissions') }}</h3>
                    </div>
                    @php
                        $permissions = json_decode($role->permissions);
                    @endphp
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="banner"></label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Products') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="1"
                                            @php
                                                if (in_array(1, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="14"
                                            @php
                                                if (in_array(14, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="28"
                                            @php
                                                if (in_array(28, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="18"
                                            @php
                                                if (in_array(18, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="2"
                                            @php
                                                if (in_array(2, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="3"
                                            @php
                                                if (in_array(3, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="4"
                                            @php
                                                if (in_array(4, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="5"
                                            @php
                                                if (in_array(5, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="6"
                                            @php
                                                if (in_array(6, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="16"
                                            @php
                                                if (in_array(16, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="17"
                                            @php
                                                if (in_array(17, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="7"
                                            @php
                                                if (in_array(7, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="8"
                                            @php
                                                if (in_array(8, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="9"
                                            @php
                                                if (in_array(9, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="10"
                                            @php
                                                if (in_array(10, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="11"
                                            @php
                                                if (in_array(11, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="12"
                                            @php
                                                if (in_array(12, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="13"
                                            @php
                                                if (in_array(13, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="15"
                                            @php
                                                if (in_array(15, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="19"
                                            @php
                                                if (in_array(19, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="20"
                                            @php
                                                if (in_array(20, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="21"
                                            @php
                                                if (in_array(21, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="22"
                                            @php
                                                if (in_array(22, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="23"
                                            @php
                                                if (in_array(23, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="24"
                                            @php
                                                if (in_array(24, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="25"
                                            @php
                                                if (in_array(25, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="26"
                                            @php
                                                if (in_array(26, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
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
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="27"
                                            @php
                                                if (in_array(27, $permissions)) {
                                                    echo 'checked';
                                                }
                                            @endphp>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group mr-2" role="group" aria-label="First group">
                                <button type="submit" name="button" value="save"
                                    class="btn btn-warning">{{ translate('Save') }}</button>
                            </div>
                            <div class="btn-group mr-2" role="group" aria-label="Third group">
                                <button type="submit" name="button" value="update"
                                    class="btn btn-primary">{{ translate('Update') }}</button>
                            </div>

                        </div>
                    </div>
                </div>

            </form>
            <!--===================================================-->
            <!--End Horizontal Form-->

        </div>
    </div>

@endsection
