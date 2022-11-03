@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Home slider') }}</h5>
                </div>
                <div class="card-body" id="demo-lft-tab-1">
                    <div class="aiz-titlebar text-left mt-2 mb-3">
                        <div class="align-items-center">
                            <div class="text-md-right">
                                <a onclick="add_slider()" class="btn btn-rounded btn-info pull-right">
                                    <span>{{ translate('Add New Slider') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <table class="table aiz-table mb-0 table-responsive" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Photo Web') }}</th>
                                <th>{{ translate('Photo Mobile') }}</th>
                                <th width="50%">{{ translate('Link / mobile link') }}</th>
                                <th>{{ translate('Published') }}</th>
                                <th width="10%">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\Slider::all() as $key => $slider)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <img src="{{ uploaded_asset($slider->{'photo_web_' . locale()}) }}" alt="Image"
                                            class="w-50px">
                                    </td>
                                    <td>
                                        <img src="{{ uploaded_asset($slider->{'photo_mobile_' . locale()}) }}" alt="Image"
                                            class="w-50px">
                                    </td>
                                    <td>{{ $slider->{'link_' . locale()} }} <br> {{ $slider->mobile_link }}</td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="update_slider_published(this)" value="{{ $slider->id }}"
                                                type="checkbox" <?php if ($slider->published == 1) {
                                            echo 'checked';
                                            } ?> >
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            onclick="edit_home_slider({{ $slider->id }})"
                                            title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('sliders.destroy', $slider->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Home banner') }} ( {{ translate('Max 3 published') }} )</h5>
                </div>
                <div class="card-body" id="demo-lft-tab-2">
                    <div class="aiz-titlebar text-left mt-2 mb-3">
                        <div class="align-items-center">
                            <div class="text-md-right">
                                <a onclick="add_banner_1()" class="btn btn-rounded btn-info pull-right">
                                    <span>{{ translate('Add New Banner') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <table class="table aiz-table mb-0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Photo') }}</th>
                                <th>{{ translate('Position') }}</th>
                                <th>{{ translate('Published') }}</th>
                                <th>{{ translate('Mobile') }}</th>
                                <th width="10%">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\Banner::where('position', 1)->get() as $key => $banner)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <img src="{{ uploaded_asset($banner->{'photo_' . locale()}) }}" alt="Image"
                                            class="w-50px">
                                    </td>
                                    <td>{{ translate('Banner Position ') }}{{ $banner->position }}</td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="update_banner_published(this)" value="{{ $banner->id }}"
                                                type="checkbox" <?php if ($banner->published == 1) {
                                            echo 'checked';
                                            } ?> >
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="update_banner_mobile(this)" value="{{ $banner->id }}"
                                                type="checkbox" <?php if ($banner->mobile_web == 1) {
                                            echo 'checked';
                                            } ?> >
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            onclick="edit_home_banner_1({{ $banner->id }})"
                                            title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('home_banners.destroy', $banner->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Home categories') }}</h5>
                </div>
                <div class="card-body" id="demo-lft-tab-4">
                    <div class="aiz-titlebar text-left mt-2 mb-3">
                        <div class="align-items-center">
                            <div class="text-md-right">
                                <a onclick="add_home_category()" class="btn btn-rounded btn-info pull-right">
                                    <span>{{ translate('Add New Category') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <table class="table aiz-table mb-0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Category') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th width="10%">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\HomeCategory::all() as $key => $home_category)
                                @if ($home_category->category != null)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $home_category->category->{'name_' . locale()} }}</td>
                                        <td>
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input onchange="update_home_category_status(this)"
                                                    value="{{ $home_category->id }}" type="checkbox" <?php
                                                    if ($home_category->status == 1) {
                                                echo 'checked';
                                                } ?> >
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                                onclick="edit_home_category({{ $home_category->id }})"
                                                title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </a>
                                            <a href="#"
                                                class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                                data-href="{{ route('home_categories.destroy', $home_category->id) }}"
                                                title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Home banner 2') }} ({{ translate('Max 3 published') }})</h5>
                </div>
                <div class="card-body" id="demo-lft-tab-3">
                    <div class="aiz-titlebar text-left mt-2 mb-3">
                        <div class="align-items-center">
                            <div class="text-md-right">
                                <a onclick="add_banner_2()" class="btn btn-rounded btn-info pull-right">
                                    <span>{{ translate('Add New Banner') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <table class="table aiz-table mb-0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Photo') }}</th>
                                <th>{{ translate('Position') }}</th>
                                <th>{{ translate('Published') }}</th>
                                <th>{{ translate('Mobile') }}</th>
                                <th width="10%">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\Banner::where('position', 2)->get() as $key => $banner)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><img src="{{ uploaded_asset($banner->{'photo_' . locale()}) }}" alt="Image"
                                            class="w-50px">
                                    </td>
                                    <td>{{ translate('Banner Position ') }}{{ $banner->position }}</td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="update_banner_published(this)" value="{{ $banner->id }}"
                                                type="checkbox" <?php if ($banner->published == 1) {
                                            echo 'checked';
                                            } ?> >
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="update_banner_mobile(this)" value="{{ $banner->id }}"
                                                type="checkbox" <?php if ($banner->mobile_web == 1) {
                                            echo 'checked';
                                            } ?> >
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            onclick="edit_home_banner_2({{ $banner->id }})"
                                            title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('home_banners.destroy', $banner->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Payment Icons') }}</h5>
                </div>
                <div class="card-body" id="demo-lft-tab-5">
                    <div class="aiz-titlebar text-left mt-2 mb-3">
                        <div class="align-items-center">
                            <div class="text-md-right">
                                <a onclick="add_new_icon()" class="btn btn-rounded btn-info pull-right">
                                    <span>{{ translate('Add New Icon') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <table class="table aiz-table mb-0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Icon') }}</th>
                                <th>{{ translate('Link') }}</th>
                                <th>{{ translate('Title') }}</th>
                                <th>{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\PaymentIcon::get() as $key => $icon)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <img src="{{ uploaded_asset($icon->icon) }}" alt="Image" class="w-30px">
                                    </td>
                                    <td>{{ $icon->link }}</td>
                                    <td>{{ $icon->{'title_' . locale()} }}</td>


                                    <td>
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            onclick="edit_icons({{ $icon->id }})" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('icons.destroy', $icon->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Nav Links') }}</h5>
                </div>
                <div class="card-body" id="demo-lft-tab-6">
                    <div class="aiz-titlebar text-left mt-2 mb-3">
                        <div class="align-items-center">
                            <div class="text-md-right">
                                <a onclick="add_new_navLink()" class="btn btn-rounded btn-info pull-right">
                                    <span>{{ translate('Add New Link') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <table class="table aiz-table mb-0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Title') }}</th>
                                <th>{{ translate('Link') }}</th>
                                <th>{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\NavLink::get() as $key => $link)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $link->{'title_' . locale()} }}</td>
                                    <td>{{ $link->link }}</td>


                                    <td>
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            onclick="edit_navlinks({{ $link->id }})"
                                            title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('navlinks.destroy', $link->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Home banner') }} ({{ translate('Top 10 Information') }})</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('top_10_settings.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="product-choose-list">
                            <div class="product-choose">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label"
                                        for="name">{{ translate('Top Categories (Max 10)') }}</label>
                                    <div class="col-lg-9">
                                        <select name="top_categories[]" class="form-control product_id aiz-selectpicker"
                                            data-live-search="true" data-selected-text-format="count" required multiple>
                                            @foreach (\App\Category::where('published', true)->select(['*', 'name_' . locale() . ' as name'])->get()
        as $key => $category)
                                                <option value="{{ $category->id }}" @if ($category->top == 1) selected @endif>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-choose-list">
                            <div class="product-choose">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label"
                                        for="name">{{ translate('Top Brands (Max 10)') }}</label>
                                    <div class="col-lg-9">
                                        <select name="top_brands[]" class="form-control product_id aiz-selectpicker"
                                            data-live-search="true" data-selected-text-format="count" required multiple>
                                            @foreach (\App\Brand::all(['*', 'name_' . locale() . ' as name']) as $key => $brand)
                                                <option value="{{ $brand->id }}" @if ($brand->top == 1) selected @endif>
                                                    {{ $brand->name }}</option>
                                            @endforeach
                                        </select>
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

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Front pages') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('frontPageStore.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="product-choose-list">
                            <div class="product-choose">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label"
                                        for="name">{{ translate('Seller Policy') }}</label>
                                    <div class="col-lg-9">
                                        <select name="seller_policy" class="form-control product_id aiz-selectpicker"
                                            data-live-search="true" data-selected-text-format="count" required>
                                            @foreach ($pages as $key => $page)
                                                <option {{ $appSetting->seller_policy == $key ? 'selected' : '' }}
                                                    value="{{ $key }}">{{ $page }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="product-choose-list">
                            <div class="product-choose">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label"
                                        for="name">{{ translate('Return Policy') }}</label>
                                    <div class="col-lg-9">
                                        <select name="return_policy" class="form-control product_id aiz-selectpicker"
                                            data-live-search="true" data-selected-text-format="count" required>
                                            @foreach ($pages as $key => $page)
                                                <option {{ $appSetting->return_policy == $key ? 'selected' : '' }}
                                                    value="{{ $key }}">{{ $page }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-choose-list">
                            <div class="product-choose">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label"
                                        for="name">{{ translate('Support Policy') }}</label>
                                    <div class="col-lg-9">
                                        <select name="support_policy" class="form-control product_id aiz-selectpicker"
                                            data-live-search="true" data-selected-text-format="count" required>
                                            @foreach ($pages as $key => $page)
                                                <option {{ $appSetting->support_policy == $key ? 'selected' : '' }}
                                                    value="{{ $key }}">{{ $page }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="product-choose-list">
                            <div class="product-choose">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label"
                                        for="name">{{ translate('Terms and conditions') }}</label>
                                    <div class="col-lg-9">
                                        <select name="terms_conditions" class="form-control product_id aiz-selectpicker"
                                            data-live-search="true" data-selected-text-format="count" required>
                                            @foreach ($pages as $key => $page)
                                                <option {{ $appSetting->terms_conditions == $key ? 'selected' : '' }}
                                                    value="{{ $key }}">{{ $page }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="product-choose-list">
                            <div class="product-choose">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label"
                                        for="name">{{ translate('Privacy policy') }}</label>
                                    <div class="col-lg-9">
                                        <select name="privacy_policy" class="form-control product_id aiz-selectpicker"
                                            data-live-search="true" data-selected-text-format="count" required>
                                            @foreach ($pages as $key => $page)
                                                <option {{ $appSetting->privacy_policy == $key ? 'selected' : '' }}
                                                    value="{{ $key }}">{{ $page }}</option>
                                            @endforeach
                                        </select>
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

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Home And Ads Page Deep Links') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
                        <tr>
                            <td>
                                {{ translate('customer products') }}
                            </td>

                            <td>
                                <a href="javascript:void(0)" title="{{ translate('Copy Deep Link') }}"
                                    class="btn btn-soft-primary btn-icon btn-circle btn-sm" onclick="copyUrl(this)"
                                    data-url="{{ route('customer.products') }}">
                                    <i class="las la-clipboard mr-2"></i>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ translate('Home') }}
                            </td>

                            <td>
                                <a href="javascript:void(0)" title="{{ translate('Copy Deep Link') }}"
                                    class="btn btn-soft-primary btn-icon btn-circle btn-sm" onclick="copyUrl(this)"
                                    data-url="{{ route('home') }}">
                                    <i class="las la-clipboard mr-2"></i>


                            </td>
                        </tr>

                        <tr>
                            <td>
                                {{ translate('Categories') }}
                            </td>

                            <td>
                                <a href="javascript:void(0)" title="{{ translate('Copy Deep Link') }}"
                                    class="btn btn-soft-primary btn-icon btn-circle btn-sm" onclick="copyUrl(this)"
                                    data-url="{{ route('categories.all') }}">
                                    <i class="las la-clipboard mr-2"></i>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ translate('Brands') }}
                            </td>

                            <td>
                                <a href="javascript:void(0)" title="{{ translate('Copy Deep Link') }}"
                                    class="btn btn-soft-primary btn-icon btn-circle btn-sm" onclick="copyUrl(this)"
                                    data-url="{{ route('brands.all') }}">
                                    <i class="las la-clipboard mr-2"></i>

                            </td>
                        </tr>
                    </table>

                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Promotion Control') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('startPages.updatePromotion') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf


                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Title English') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" value="{{ $appSetting->promotion_title_en }}"
                                    name="promotion_title_en" placeholder="{{ translate('Title English') }}"
                                    id="title_en" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="name">{{ translate('Min Description En') }}</label>
                            <div class="col-md-9">
                                <textarea type="text" placeholder="{{ translate('Min Description En') }}" id="name"
                                    name="promotion_desc_en" class="form-control"
                                    required>{{ $appSetting->promotion_desc_en }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Title Arabic') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="promotion_title_ar"
                                    value="{{ $appSetting->promotion_title_ar }}"
                                    placeholder="{{ translate('Title Arabic') }}" id="title_ar" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="name">{{ translate('Min Description Ar') }}</label>
                            <div class="col-md-9">
                                <textarea type="text" placeholder="{{ translate('Min Description Ar') }}" id="name"
                                    name="promotion_desc_ar" class="form-control"
                                    required>{{ $appSetting->promotion_desc_ar }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Arabic Link') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="promotion_link_ar"
                                    value="{{ $appSetting->promotion_link_ar }}" placeholder="{{ translate('Link') }}"
                                    id="link">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('English Link') }}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="promotion_link_en"
                                    value="{{ $appSetting->promotion_link_en }}" placeholder="{{ translate('Link') }}"
                                    id="link">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Photo') }}
                            </label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="promotion_photo"
                                        value="{{ $appSetting->promotion_photo }}" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('APear') }}</label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="promotion_appear" <?php if
                                        ($appSetting->promotion_appear == 1) {
                                    echo 'checked';
                                    } ?> >
                                    <span class="slider round"></span>
                                </label>
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
@section('modal')
    @include('modals.delete_modal')
@endsection
@section('script')

    <script type="text/javascript">
        function copyUrl(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
        }

        function updateSettings(el, type) {
            if ($(el).is(':checked')) {
                var value = 1;
            } else {
                var value = 0;
            }
            $.post(`{{ route('business_settings.update.activation') }}`, {
                _token: '{{ csrf_token() }}',
                type: type,
                value: value
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function add_slider() {
            $.get(`{{ route('sliders.create') }}`, {}, function(data) {
                $('#demo-lft-tab-1').html(data);
            });
        }

        function add_new_icon() {
            $.get(`{{ route('icons.create') }}`, {}, function(data) {
                $('#demo-lft-tab-5').html(data);
            });
        }

        function add_new_navLink() {
            $.get(`{{ route('navlinks.create') }}`, {}, function(data) {
                $('#demo-lft-tab-6').html(data);
            });
        }

        function edit_navlinks(id) {
            var url = `{{ route('navlinks.edit', 'navlinks_id') }}`;
            url = url.replace('navlinks_id', id);
            $.get(url, {}, function(data) {
                $('#demo-lft-tab-6').html(data);
                AIZ.plugins.fooTable();
            });
        }

        function edit_icons(id) {
            var url = `{{ route('icons.edit', 'icon_id') }}`;
            url = url.replace('icon_id', id);
            $.get(url, {}, function(data) {
                $('#demo-lft-tab-5').html(data);
                AIZ.plugins.fooTable();
            });
        }

        function edit_home_slider(id) {
            var url = `{{ route('sliders.edit', 'home_slider_id') }}`;
            url = url.replace('home_slider_id', id);
            $.get(url, {}, function(data) {
                $('#demo-lft-tab-1').html(data);
                AIZ.plugins.fooTable();
            });
        }

        function add_banner_1() {
            $.get(`{{ route('home_banners.create', 1) }}`, {}, function(data) {
                $('#demo-lft-tab-2').html(data);
            });
        }

        function add_banner_2() {
            $.get(`{{ route('home_banners.create', 2) }}`, {}, function(data) {
                $('#demo-lft-tab-3').html(data);
            });
        }

        function edit_home_banner_1(id) {
            var url = `{{ route('home_banners.edit', 'home_banner_id') }}`;
            url = url.replace('home_banner_id', id);
            $.get(url, {}, function(data) {
                $('#demo-lft-tab-2').html(data);
            });
        }

        function edit_home_banner_2(id) {
            var url = `{{ route('home_banners.edit', 'home_banner_id') }}`;
            url = url.replace('home_banner_id', id);
            $.get(url, {}, function(data) {
                $('#demo-lft-tab-3').html(data);
                $('.demo-select2-placeholder').select2();
            });
        }

        function add_home_category() {
            $.get(`{{ route('home_categories.create') }}`, {}, function(data) {
                $('#demo-lft-tab-4').html(data);
                $('.demo-select2-placeholder').select2();
            });
        }

        function edit_home_category(id) {
            var url = `{{ route('home_categories.edit', 'home_category_id') }}`;
            url = url.replace('home_category_id', id);
            $.get(url, {}, function(data) {
                $('#demo-lft-tab-4').html(data);
                $('.demo-select2-placeholder').select2();
            });
        }

        function update_home_category_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post(`{{ route('home_categories.update_status') }}`, {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success',
                        '{{ translate('Home Page Category status updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_banner_published(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post(`{{ route('home_banners.update_status') }}`, {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Banner status updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_banner_mobile(el) {
            if (el.checked) {
                var mobile = 1;
            } else {
                var mobile = 0;
            }
            $.post(`{{ route('home_banners.update_banner_mobile') }}`, {
                _token: '{{ csrf_token() }}',
                id: el.value,
                mobile: mobile
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Banner mobile updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_slider_published(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            var url = `{{ route('sliders.update', 'slider_id') }}`;
            url = url.replace('slider_id', el.value);

            $.post(url, {
                _token: '{{ csrf_token() }}',
                status: status,
                _method: 'PATCH'
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Published sliders updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

    </script>

@endsection
