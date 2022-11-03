@extends('layouts.app')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ url('./public/lou-multi/css/multi-select.css') }}">
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h1 class="mb-0 h6">{{ translate('Edit Product') }}</h5>
    </div>
    <div class="">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="form form-horizontal mar-top" action="{{ route('products.update', $product->id) }}" method="POST"
            enctype="multipart/form-data" id="choice_form">
            <input name="_method" type="hidden" value="POST">
            <input type="hidden" name="id" value="{{ $product->id }}">
            <div class="row gutters-5">
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0 h6">{{ translate('Product Information') }}</h3>
                        </div>
                        <div class="card-body">
                            @if ($product->added_by == 'seller' && Auth::user()->user_type == 'admin')
                                <div class="form-group row" id="user_id">
                                    <label class="col-md-3 col-from-label">{{ translate('Select Seller') }}</label>
                                    <div class="col-md-8">
                                        <select class="form-control aiz-selectpicker" name="user_id" id="user_id"
                                            data-live-search="true">
                                            <option value="">{{ 'Select Seller' }}</option>
                                            @foreach (\App\User::where('user_type', 'seller')->get() as $seller)
                                                <option value="{{ $seller->id }}"
                                                    {{ $product->user_id == $seller->id ? 'selected' : '' }}>
                                                    {{ $seller->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Product Name English') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name_en"
                                        placeholder="{{ translate('Product Name English') }}"
                                        value="{{ $product->name_en }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Product Name Arabic') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name_ar"
                                        placeholder="{{ translate('Product Name Arabic') }}"
                                        value="{{ $product->name_ar }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Product Country Arabic') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="country_ar"
                                        placeholder="{{ translate('Product Country Arabic') }}"
                                        value="{{ $product->country_ar }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Product Country English') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="country_en"
                                        placeholder="{{ translate('Product Country English') }}"
                                        value="{{ $product->country_en }}">
                                </div>
                            </div>

                            <div class="form-group row" id="light_heavy_shipping">
                                <label class="col-lg-3 col-from-label">{{ translate('Heavy / Light Product') }}</label>
                                <div class="col-lg-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true"
                                        name="light_heavy_shipping" id="light_heavy_shipping" required>
                                        <option value="light" <?php if ($product->light_heavy_shipping == 'light') {
    echo 'selected';
} ?>>{{ translate('Light') }}</option>
                                        <option value="heavy" <?php if ($product->light_heavy_shipping == 'heavy') {
    echo 'selected';
} ?>>{{ translate('Heavy') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" id="subsubcategory">
                                <label class="col-lg-3 col-from-label">{{ translate('Category') }}</label>
                                <div class="col-lg-8">
                                    <select class="form-control" name="subsubcategory_id_multy[]"
                                        id="subsubcategory_id_multy" multiple="multiple">
                                        @forelse ($subsubcategory_id_multy as $key => $sub)
                                            <option
                                                {{ in_array($key, $subsubcategory_id_multy_selected) ? 'selected' : '' }}
                                                value="{{ $key }}">{{ $sub }}</option>
                                        @empty

                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" id="brand">
                                <label class="col-lg-3 col-from-label">{{ translate('Brand') }}</label>
                                <div class="col-lg-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true" name="brand_id"
                                        id="brand_id">
                                        <option value="">{{ 'Select Brand' }}</option>
                                        @foreach (\App\Brand::select(['*', 'name_' . locale() . ' as name'])->get() as $brand)
                                            <option value="{{ $brand->id }}" @if ($product->brand_id == $brand->id) selected @endif>
                                                {{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Unit') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="unit"
                                        placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}"
                                        value="{{ $product->unit }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Minimum Qty') }}</label>
                                <div class="col-lg-8">
                                    <input type="number" class="form-control" name="min_qty"
                                        value="@if ($product->min_qty <= 1){{ 1 }}@else{{ $product->min_qty }}@endif" min="1" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Tags English') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control aiz-tag-input" name="tags_en[]" id="tags"
                                        value="{{ $product->tags_en }}"
                                        placeholder="{{ translate('Type to add a tag English') }}" data-role="tagsinput">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Tags Arabic') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control aiz-tag-input" name="tags_ar[]" id="tags"
                                        value="{{ $product->tags_ar }}"
                                        placeholder="{{ translate('Type to add a tag Arabic') }}" data-role="tagsinput">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Hash Tags') }}</label>
                                <div class="col-lg-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true" multiple
                                        name="hash_tags[]" id="hash_tags">
                                        <option value="">{{ 'Select HashTags' }}</option>
                                        @foreach ($hashtags as $hashtag)
                                            <option value="{{ $hashtag->id }}" @if (in_array($hashtag->id, explode(',', $product->hashtag_ids))) selected @endif>
                                                {{ $hashtag->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @php
                                $pos_addon = \App\Addon::where('unique_identifier', 'pos_system')->first();
                            @endphp
                            @if ($pos_addon != null && $pos_addon->activated == 1)
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{ translate('Barcode') }}</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="barcode"
                                            placeholder="{{ translate('Barcode') }}" value="{{ $product->barcode }}">
                                    </div>
                                </div>
                            @endif

                            @php
                                $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
                            @endphp
                            @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{ translate('Refundable') }}</label>
                                    <div class="col-lg-8">
                                        <label class="aiz-switch aiz-switch-success mb-0" style="margin-top:5px;">
                                            <input type="checkbox" name="refundable" @if ($product->refundable == 1) checked @endif>
                                            <span class="slider round"></span></label>
                                        </label>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0 h6">{{ translate('Product Images') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail">{{ translate('Gallery Images') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image"
                                        data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="photos" value="{{ $product->photos }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail">{{ translate('Thumbnail Image') }}
                                    <small>(290x300)</small></label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="thumbnail_img" value="{{ $product->thumbnail_img }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0 h6">{{ translate('Product Variation') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <input type="text" class="form-control" value="{{ translate('Colors') }}"
                                        disabled>
                                </div>
                                <div class="col-lg-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true"
                                        data-selected-text-format="count" name="colors[]" id="colors" multiple>
                                        @foreach (\App\Color::orderBy('name', 'asc')->get() as $key => $color)
                                            <option value="{{ $color->code }}"
                                                data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"
                                                <?php if (in_array($color->code, json_decode($product->colors))) {
                                                    echo 'selected';
                                                } ?>></option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-1">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" type="checkbox" name="colors_active" <?php if (count(json_decode($product->colors)) > 0) {
    echo 'checked';
} ?>>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <input type="text" class="form-control" value="{{ translate('Attributes') }}"
                                        disabled>
                                </div>
                                <div class="col-lg-8">
                                    <select name="choice_attributes[]" id="choice_attributes"
                                        data-selected-text-format="count" data-live-search="true"
                                        class="form-control aiz-selectpicker" multiple
                                        data-placeholder="{{ translate('Choose Attributes') }}">
                                        @foreach (\App\Attribute::all(['*', 'name_' . locale() . ' as name']) as $key => $attribute)
                                            <option value="{{ $attribute->id }}" @if ($product->attributes != null && in_array($attribute->id, json_decode($product->attributes, true))) selected @endif>
                                                {{ $attribute->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="">
                                <p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}
                                </p>
                                <br>
                            </div>

                            <div class="customer_choice_options" id="customer_choice_options">
                                @foreach (json_decode($product->choice_options) as $key => $choice_option)
                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <input type="hidden" name="choice_no[]"
                                                value="{{ $choice_option->attribute_id }}">
                                            <input type="text" class="form-control" name="choice[]"
                                                value="{{ \App\Attribute::find($choice_option->attribute_id)->name }}"
                                                placeholder="{{ translate('Choice Title') }}" disabled>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control aiz-tag-input"
                                                name="choice_options_{{ $choice_option->attribute_id }}[]"
                                                placeholder="{{ translate('Enter choice values') }}"
                                                value="{{ implode(',', $choice_option->values) }}"
                                                data-on-change="update_sku">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0 h6">{{ translate('Product price + stock') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Unit price') }}</label>
                                <div class="col-lg-6">
                                    <input type="text" placeholder="{{ translate('Unit price') }}" name="unit_price"
                                        class="form-control" value="{{ $product->unit_price }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Purchase price') }}</label>
                                <div class="col-lg-6">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('Purchase price') }}" name="purchase_price"
                                        class="form-control" value="{{ $product->purchase_price }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Discount') }}</label>
                                <div class="col-lg-6">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('Discount') }}" name="discount" class="form-control"
                                        value="{{ $product->discount }}" required>
                                </div>
                                <div class="col-lg-3">
                                    <select class="form-control aiz-selectpicker" name="discount_type" required>
                                        <option value="amount" <?php if ($product->discount_type == 'amount') {
    echo 'selected';
} ?>>{{ translate('Flat') }}</option>
                                        <option value="percent" <?php if ($product->discount_type == 'percent') {
    echo 'selected';
} ?>>{{ translate('Percent') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" id="quantity">
                                <label class="col-lg-3 col-from-label">{{ translate('Quantity') }}</label>
                                <div class="col-lg-6">
                                    <input type="number" lang="en" value="{{ $product->current_stock }}" step="1"
                                        placeholder="{{ translate('Quantity') }}" name="current_stock"
                                        class="form-control" required>
                                </div>
                            </div>
                            <br>
                            <div class="sku_combination" id="sku_combination">

                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0 h6">{{ translate('Product Description') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Description English') }}</label>
                                <div class="col-lg-9">
                                    <textarea class="aiz-text-editor"
                                        name="description_en">{{ $product->description_en }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Description Arabic') }}</label>
                                <div class="col-lg-9">
                                    <textarea class="aiz-text-editor"
                                        name="description_ar">{{ $product->description_ar }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0 h6">{{ translate('SEO Meta Tags') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Meta Title English') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" value="{{ $product->meta_title_en }}"
                                        name="meta_title_en" placeholder="{{ translate('Meta Title English') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Meta Title Arabic') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" value="{{ $product->meta_title_ar }}"
                                        name="meta_title_ar" placeholder="{{ translate('Meta Title Arabic') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Description English') }}</label>
                                <div class="col-lg-8">
                                    <textarea name="meta_description_en" rows="8"
                                        class="form-control">{{ $product->meta_description_en }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Description Arabic') }}</label>
                                <div class="col-lg-8">
                                    <textarea name="meta_description_ar" rows="8"
                                        class="form-control">{{ $product->meta_description_ar }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail">{{ translate('Meta Image') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="meta_img" value="{{ $product->meta_img }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-4">
                    @if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'product_wise_shipping')
                        <div class="card">
                            <div class="card-header">
                                <h3 class="mb-0 h6">{{ translate('Product Shipping Cost') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{ translate('Free Shipping') }}</label>
                                    <div class="col-md-6">

                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="radio" name="shipping_type" value="free" @if ($product->shipping_type == 'free') checked @endif>
                                            <span></span>
                                        </label>

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <div class="card-heading">
                                            <h3 class="mb-0 h6">{{ translate('Flat Rate') }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-from-label">{{ translate('Status') }}</label>
                                            <div class="col-lg-8">
                                                <label class="aiz-switch aiz-switch-success mb-0">
                                                    <input type="radio" name="shipping_type" value="flat_rate"
                                                        @if ($product->shipping_type == 'flat_rate') checked @endif>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-lg-3 col-from-label">{{ translate('Shipping cost') }}</label>
                                            <div class="col-lg-8">
                                                <input type="number" min="0" step="0.01"
                                                    placeholder="{{ translate('Shipping cost') }}"
                                                    name="flat_shipping_cost" class="form-control"
                                                    value="{{ $product->shipping_cost }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Featured') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                                        <div class="col-md-6">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input type="checkbox" name="featured" value="1" @if ($product->featured == 1) checked @endif>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Todays Deal') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                                        <div class="col-md-6">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input type="checkbox" name="todays_deal" value="1"
                                                    @if ($product->todays_deal == 1) checked @endif>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Category For Calculation') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row" id="category">
                                <label class="col-md-3 col-from-label">{{ translate('Category') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="category_id" id="category_id"
                                        data-selected="{{ $product->category_id }}" data-live-search="true" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @foreach ($category->childrenCategories as $childCategory)
                                                @include('categories.child_category', ['child_category' => $childCategory])
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Flash Deal') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="name">
                                    {{ translate('Add To Flash') }}
                                </label>
                                <select class="form-control aiz-selectpicker" name="flash_deal_id" id="video_provider">
                                    <option value="">Choose Flash Title</option>
                                    @foreach (\App\FlashDeal::where('status', 1)->get() as $flash_deal)
                                        <option value="{{ $flash_deal->id }}" @if ($product->flash_deal_product && $product->flash_deal_product->flash_deal_id == $flash_deal->id) selected @endif>
                                            {{ $flash_deal->{'title_' . locale()} }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="name">
                                    {{ translate('Discount') }}
                                </label>
                                <input type="number" name="flash_discount"
                                    value="{{ $product->flash_deal_product ? $product->flash_deal_product->discount : '0' }}"
                                    min="0" step="1" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="name">
                                    {{ translate('Discount Type') }}
                                </label>
                                <select class="form-control aiz-selectpicker" name="flash_discount_type" id="">
                                    <option value="">Choose Discount Type</option>
                                    <option value="amount" @if ($product->flash_deal_product && $product->flash_deal_product->discount_type == 'amount') selected @endif>
                                        {{ translate('Flat') }}
                                    </option>
                                    <option value="percent" @if ($product->flash_deal_product && $product->flash_deal_product->discount_type == 'percent') selected @endif>
                                        {{ translate('Percent') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('VAT & Tax') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <label class="col-lg-3 col-from-label">{{ translate('Tax') }}</label>
                                <div class="col-lg-6">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ translate('tax') }}" name="tax" class="form-control"
                                        value="{{ $product->tax }}" required>
                                </div>
                                <div class="col-lg-3">
                                    <select class="form-control aiz-selectpicker" name="tax_type" required>
                                        <option value="amount" <?php if ($product->tax_type == 'amount') {
    echo 'selected';
} ?>>{{ translate('Flat') }}</option>
                                        <option value="percent" <?php if ($product->tax_type == 'percent') {
    echo 'selected';
} ?>>{{ translate('Percent') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0 h6">{{ translate('PDF Specification') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail">{{ translate('PDF Specification') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="document">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="pdf" value="{{ $product->pdf }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0 h6">{{ translate('Product Videos') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Video Provider') }}</label>
                                <div class="col-lg-8">
                                    <select class="form-control aiz-selectpicker" data-live-search="true"
                                        name="video_provider" id="video_provider">
                                        <option value="youtube" <?php if ($product->video_provider == 'youtube') {
    echo 'selected';
} ?>>{{ translate('Youtube') }}</option>
                                        <option value="dailymotion" <?php if ($product->video_provider == 'dailymotion') {
    echo 'selected';
} ?>>{{ translate('Dailymotion') }}
                                        </option>
                                        <option value="vimeo" <?php if ($product->video_provider == 'vimeo') {
    echo 'selected';
} ?>>{{ translate('Vimeo') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Video Link') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="video_link"
                                        value="{{ $product->video_link }}"
                                        placeholder="{{ translate('Video Link') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0 h6">{{ translate('Slug') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Slug English') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" value="{{ $product->slug_en }}" class="form-control"
                                        id="slug_en" name="slug_en" required
                                        placeholder="{{ translate('Slug English') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Slug Arabic') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" value="{{ $product->slug_ar }}"
                                        id="slug_ar" name="slug_ar" required
                                        placeholder="{{ translate('Meta Title Arabic') }}">
                                </div>
                            </div>
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
            @csrf
        </form>
    </div>

@endsection

@section('script')
    <script src="{{ url('./public/lou-multi/js/jquery.multi-select.js') }}"></script>
    <script src="{{ url('./public/jquery.quicksearch.js') }}"></script>
    <script type="text/javascript">
        $('#subsubcategory_id_multy').multiSelect({
            selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder=''>",
            selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder=''>",
            afterInit: function(ms) {
                var that = this,
                    $selectableSearch = this.$selectableUl.prev(),
                    $selectionSearch = this.$selectionUl.prev(),
                    selectableSearchString = '#' + this.$container.attr('id') +
                    ' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#' + this.$container.attr('id') +
                    ' .ms-elem-selection.ms-selected';

                this.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function(e) {
                        if (e.which === 40) {
                            this.$selectableUl.focus();
                            return false;
                        }
                    });

                this.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function(e) {
                        if (e.which == 40) {
                            this.$selectionUl.focus();
                            return false;
                        }
                    });
            },
            afterSelect: function() {
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function() {
                this.qs1.cache();
                this.qs2.cache();
            }
        });
        // var i = $('input[name="choice_no[]"').last().val();
        // if(isNaN(i)){
        // 	i =0;
        // }

        function add_more_customer_choice_option(i, name) {
            $('#customer_choice_options').append(
                '<div class="form-group row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' +
                i + '"><input type="text" class="form-control" name="choice[]" value="' + name +
                '" placeholder="{{ translate('Choice Title') }}" readonly></div><div class="col-md-8"><input type="text" class="form-control aiz-tag-input" name="choice_options_' +
                i +
                '[]" placeholder="{{ translate('Enter choice values') }}" data-on-change="update_sku"></div></div>');

            AIZ.plugins.tagify();
        }

        $('input[name="colors_active"]').on('change', function() {
            if (!$('input[name="colors_active"]').is(':checked')) {
                $('#colors').prop('disabled', true);
            } else {
                $('#colors').prop('disabled', false);
            }
            update_sku();
        });

        $('#colors').on('change', function() {
            update_sku();
        });

        // $('input[name="unit_price"]').on('keyup', function() {
        //     update_sku();
        // });

        function delete_row(em) {
            $(em).closest('.form-group').remove();
            update_sku();
        }

        function delete_variant(em) {
            $(em).closest('.variant').remove();
        }

        function update_sku() {
            $.ajax({
                type: "POST",
                url: '{{ route('products.sku_combination_edit') }}',
                data: $('#choice_form').serialize(),
                success: function(data) {
                    $('#sku_combination').html(data);
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        }
        AIZ.plugins.tagify();


        $(document).ready(function() {
            // get_subcategories_by_category();
            // setTimeout(() => {
            //     $('#subsubcategory_id_multy').multiSelect('refresh');
            // }, 2000);

            update_sku();

            $('.remove-files').on('click', function() {
                $(this).parents(".col-md-4").remove();
            });
        });

        // $('#category_id').on('change', function() {
        //     get_subcategories_by_category();
        //     $('#subsubcategory_id_multy').multiSelect('refresh');
        // });

        // $('#subcategory_id').on('change', function() {
        //     get_subsubcategories_by_subcategory();
        //     $('#subsubcategory_id_multy').multiSelect('refresh');
        // });

        // $('#subsubcategory_id').on('change', function() {
        //get_brands_by_subsubcategory();
        //get_attributes_by_subsubcategory();
        // });

        $('#choice_attributes').on('change', function() {
            $.each($("#choice_attributes option:selected"), function(j, attribute) {
                flag = false;
                $('input[name="choice_no[]"]').each(function(i, choice_no) {
                    if ($(attribute).val() == $(choice_no).val()) {
                        flag = true;
                    }
                });
                if (!flag) {
                    add_more_customer_choice_option($(attribute).val(), $(attribute).text());
                }
            });

            var str = @php echo $product->attributes @endphp;

            $.each(str, function(index, value) {
                flag = false;
                $.each($("#choice_attributes option:selected"), function(j, attribute) {
                    if (value == $(attribute).val()) {
                        flag = true;
                    }
                });
                if (!flag) {
                    $('input[name="choice_no[]"][value="' + value + '"]').parent().parent().remove();
                }
            });

            update_sku();
        });


        $(document).ready(function() {

        });
    </script>

@endsection
