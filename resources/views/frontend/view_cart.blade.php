@extends('frontend.layouts.app')

@section('content')
    <style>
        @import url("https://fonts.googleapis.com/css?family=Raleway:200");

        .digit-group .splitter {
            padding: 0 5px;
            color: white;
            font-size: 24px;
        }

        .prompt {
            margin-bottom: 20px;
            font-size: 20px;
            color: white;
        }

        .complete-add-adress-container,
        .new-phone-container,
        .otp-phone-container,
        .existing-phone-container,
        .loading-address-img,
        #bloked_phone_message,
        .enabled-phone {
            display: none;
        }

    </style>
    <section class="slice-xs sct-color-2 border-bottom">
        <div class="container container-sm">
            <div class="row cols-delimited justify-content-center">
                <div class="col">
                    <div class="icon-block icon-block--style-1-v5 text-center active">
                        <div class="block-icon mb-0">
                            <i class="la la-shopping-cart"></i>
                        </div>
                        <div class="block-content d-none d-md-block">
                            <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">
                                {{ translate('1. My Cart') }}
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="icon-block icon-block--style-1-v5 text-center">
                        <div class="block-icon mb-0 c-gray-light">
                            <i class="la la-truck"></i>
                        </div>
                        <div class="block-content d-none d-md-block">
                            <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">
                                {{ translate('2. Shipping info') }}
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="icon-block icon-block--style-1-v5 text-center">
                        <div class="block-icon c-gray-light mb-0">
                            <i class="la la-credit-card"></i>
                        </div>
                        <div class="block-content d-none d-md-block">
                            <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">
                                {{ translate('3. Payment') }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="py-4 gry-bg" id="cart-summary">
        <div class="container">
            @if (Session::has('cart'))
                <div class="row cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-xl-8">

                        @include('frontend.message')
                        <form class="form-default" data-toggle="validator"
                            action="{{ route('checkout.store_shipping_infostore') }}" role="form" method="POST">

                            <div class="form-default bg-white p-4">
                                <div class="">
                                    <div class="">
                                        <table class="table-cart border-bottom">
                                            <thead>
                                                <tr>
                                                    <th class="product-image"></th>
                                                    <th class="product-name">{{ translate('Product') }}</th>
                                                    <th class="product-price d-none d-lg-table-cell">
                                                        {{ translate('Price') }}
                                                    </th>
                                                    <th class="product-quanity d-none d-md-table-cell">
                                                        {{ translate('Quantity') }}
                                                    </th>
                                                    <th class="product-total">{{ translate('Total') }}</th>
                                                    <th class="product-remove"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                $total = 0;
                                                @endphp
                                                @foreach (Session::get('cart') as $key => $cartItem)
                                                    @php
                                                    $product = \App\Product::find($cartItem['id']);
                                                    $total = $total + $cartItem['price']*$cartItem['quantity'];
                                                    $product_name_with_choice = $product->{'name_'.locale()};
                                                    if ($cartItem['variant'] != null) {
                                                    $product_name_with_choice = $product->{'name_'.locale()}.' -
                                                    '.$cartItem['variant'];
                                                    }

                                                    @endphp
                                                    <tr class="cart-item">
                                                        <td class="product-image">
                                                            <a href="#" class="mr-3">
                                                                <img loading="lazy"
                                                                    src="{{ uploaded_asset($product->thumbnail_img) }}">
                                                            </a>
                                                        </td>

                                                        <td class="product-name">
                                                            <span class="pr-4 d-block">{{ $product_name_with_choice }}</span>
                                                        </td>

                                                        <td class="product-price d-none d-lg-table-cell">
                                                            <span class="pr-3 d-block">{{ single_price($cartItem['price']) }}</span>
                                                        </td>

                                                        <td class="product-quantity d-none d-md-table-cell">
                                                            @if ($cartItem['digital'] != 1)
                                                                <div class="input-group input-group--style-2 pr-4"
                                                                    style="width: 130px;">
                                                                    <span class="input-group-btn">
                                                                        <button class="btn btn-number" type="button"
                                                                            data-type="minus"
                                                                            data-field="quantity[{{ $key }}]">
                                                                            <i class="la la-minus"></i>
                                                                        </button>
                                                                    </span>
                                                                    <input type="text" name="quantity[{{ $key }}]"
                                                                        class="form-control h-auto input-number"
                                                                        placeholder="1" value="{{ $cartItem['quantity'] }}"
                                                                        min="1" max="10"
                                                                        onchange="updateQuantity({{ $key }}, this)">
                                                                    <span class="input-group-btn">
                                                                        <button class="btn btn-number" type="button"
                                                                            data-type="plus"
                                                                            data-field="quantity[{{ $key }}]">
                                                                            <i class="la la-plus"></i>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td class="product-total">
                                                            <span>{{ single_price(($cartItem['price'] + $cartItem['tax']) * $cartItem['quantity']) }}</span>
                                                        </td>
                                                        <td class="product-remove">
                                                            <a href="#" onclick="removeFromCartView(event, {{ $key }})"
                                                                class="text-right pl-4">
                                                                <i class="la la-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @csrf
                                        @if (\App\BusinessSetting::where('type', 'guest_checkout_active')->first()->value == 1 || Auth::check())
                                        <section class="py-4 gry-bg">
                                            <div class="container">
                                                <div class="row cols-xs-space cols-sm-space cols-md-space">
                                                    <div class="col-lg-8">
                                                        @if (Auth::check())
                                                            <div class="row gutters-5">
                                                                @foreach (Auth::user()->addresses as $key => $address)
                                                                    <div class="col-md-6">
                                                                        <label class="aiz-megabox d-block bg-white">
                                                                            <input type="radio" name="address_id"
                                                                                value="{{ $address->id }}" @if ($address->set_default)
                                                                            checked
                                                                @endif required>
                                                                <span class="d-flex p-3 aiz-megabox-elem">
                                                                    <span
                                                                        class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                    <span class="flex-grow-1 pl-3">
                                                                        <div>
                                                                            <span class="alpha-6">{{ translate('Address') }}:</span>
                                                                            <span class="strong-600 ml-2">{{ $address->address }}</span>
                                                                        </div>
                                                                        @if ($address->addressRegion)
                                                                            <div>
                                                                                <span class="alpha-6">{{ translate('Region') }}:</span>
                                                                                <span class="strong-600 ml-2">{{ $address->addressRegion->{'name_' . locale()} }}</span>
                                                                            </div>
                                                                        @endif
                                                                        @if ($address->addressCity)
                                                                            <div>
                                                                                <span class="alpha-6">{{ translate('City') }}:</span>
                                                                                <span class="strong-600 ml-2">{{ $address->addressCity->{'name_' . locale()} }}</span>
                                                                            </div>
                                                                        @endif

                                                                        @if ($address->addressProvince)
                                                                            <div>
                                                                                <span class="alpha-6">{{ translate('Province') }}:</span>
                                                                                <span class="strong-600 ml-2">{{ $address->addressProvince->{'name_' . locale()} }}</span>
                                                                            </div>
                                                                        @endif

                                                                        <div>
                                                                            <span class="alpha-6">{{ translate('Country') }}:</span>
                                                                            <span class="strong-600 ml-2">{{ $address->addressCountry->{'name_' . locale()} }}</span>
                                                                        </div>
                                                                        <div>
                                                                            <span class="alpha-6">{{ translate('Phone') }}:</span>
                                                                            <span class="strong-600 ml-2">{{ $address->phone }}</span>
                                                                        </div>
                                                                    </span>
                                                                </span>
                                                                </label>
                                                            </div>
                                                        @endforeach

                                                        
                                                        <input type="hidden" name="checkout_type" value="logged">
                                                        <div class="col-md-6 mx-auto" onclick="add_new_address()">
                                                            <div class="border p-3 rounded mb-3 c-pointer text-center bg-white">
                                                                <i class="la la-plus la-2x"></i>
                                                                <div class="alpha-7">{{ translate('Add New Address') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    @if (\App\BusinessSetting::where('type', 'guest_checkout_active')->first()->value == 1)
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">{{ translate('Name') }}</label>
                                                                            <input type="text" class="form-control" name="name" placeholder="{{ translate('Name') }}" required>
                                                                            @error('name')
                                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">{{ translate('Email') }}</label>
                                                                            <input type="email" class="form-control" name="email" placeholder="{{ translate('Email') }}" required>
                                                                            @error('email')
                                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">{{ translate('Address') }}</label>
                                                                            <input type="text" class="form-control" name="address" placeholder="{{ translate('Address') }}" required>
                                                                            @error('address')
                                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label">{{ translate('Select your country') }}</label>
                                                                            <select class="form-control custome-control"
                                                                                onchange="get_provinces(this)"
                                                                                data-placeholder="{{ translate('Select your country') }}"
                                                                                id="country_select" data-live-search="true"
                                                                                name="country">
                                                                                @foreach (\App\Country::where('status', 1)->select(['*', 'name_' . locale() . ' as name'])->get() as $key => $country)
                                                                                    <option value="{{ $country->id }}">
                                                                                        {{ $country->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label>{{ translate('Provinces') }}</label>
                                                                    
                                                                        <select class="form-control mb-3 selectpicker" onchange="get_cities(this)"
                                                                            data-placeholder="{{ translate('Your Province') }}" id="Province_select" name="province" required>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group has-feedback">
                                                                            <label class="control-label">{{ translate('City') }}</label>
                                                                            <select class="form-control mb-3 selectpicker"
                                                                                onchange="get_regions(this)" data-placeholder="{{ translate('Your City') }}" id="city_select" name="city" required>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="control-label">{{ translate('Region') }}</label>
                                                                        <select class="form-control mb-3 selectpicker" data-placeholder="{{ translate('Select Your Region') }}" id="region_select" name="region" required>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group has-feedback">
                                                                            <label class="control-label">{{ translate('Phone') }}</label>
                                                                            <input type="number" min="0" class="form-control" placeholder="{{ translate('Phone') }}" name="phone" required>
                                                                            @error('phone')
                                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="checkout_type" value="guest">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
            </form>
        </div>
        </div>
        </div>
    </section>
    @endif
    </div>
    </div>

    <div class="row align-items-center pt-4">
        <div class="col-md-6">
            <a href="{{ route('home') }}" class="link link--style-3">
                <i class="la la-mail-reply"></i>
                {{ translate('Return to shop') }}
            </a>
        </div>
        <div class="col-md-6 text-right">
            @if (Auth::check())
                <button type="submit"
                    class="btn btn-styled btn-base-1">{{ translate('Continue to Delivery Info') }}</a></button>
            @else
                @if (\App\BusinessSetting::where('type', 'guest_checkout_active')->first()->value == 1)
                    <button class="btn btn-styled btn-base-1">{{ translate('Continue to Shipping') }}</button>
                @else
                    <button type="button" class="btn btn-styled btn-base-1" onclick="showCheckoutModal()">{{ translate('Continue to Shipping') }}</button>
                @endif
                
            @endif
        </div>
    </div>
    </div>
    </form>


    <div class="modal fade" id="new-address-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" style="height: 100%" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ translate('New Address') }}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="container" id="add-adress-fill-container">
                    <br>
                    <h6 id="add-adress-title" class="text-center">
                        {{ translate('Add Address with Existing Phone Or New Phone') }}
                    </h6>
                    <div class="loading-address-img text-center">
                        <img id="phone-v-loading-2" style="width: 40%;" src="{{ my_asset('frontend/images/loading.gif') }}">

                    </div>
                    <div class="existing-new-phone-container">
                        <br>

                        <div class="row text-center">
                            <div class="col-md"></div>
                            <div class="col-md">
                                <input type="button" id="existing-phone-button" class="btn btn-base-1"
                                    value="{{ translate('Existing') }}">
                            </div>
                            <div class="col-md">
                                <input type="button" id="new-phone-button" class="btn btn-base-1"
                                    value="{{ translate('New Phone') }}">

                            </div>
                            <div class="col-md"></div>
                        </div>
                    </div>
                    <div class="existing-phone-container">
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Phone') }}</label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    {{-- <select class="form-control mb-3 selectpicker" id="exist_phones" onchange="setInputPhone(this)"
                                        data-placeholder="{{ translate('Select your phone') }}">
                                        <option id="empty_select_option" selected disabled>{{ translate('Select Your Phone') }}</option>
                                        @if (Auth::check())
                                        @foreach (\App\Phone::where(['user_id' => Auth::user()->id, 'status' => 'actived'])->select('id', 'phone')->get() as $key => $phonee)
                                            <option value="{{ $phonee->phone }}">{{ $phonee->phone }}</option>
                                        @endforeach
                                        @endif
                                    </select> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="new-phone-container">
                        <br>

                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md">
                                <input type="text" class="form-control mb-3 text-center" id="addressPhone"
                                    placeholder="{{ translate('Your phone number') }}" name="phone" value="" required>

                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8" style="text-align: center">
                                <button type="button" id="send-phone-verification-code-button11"
                                    onclick="sendVerificationCode()" class="btn btn-base-1">{{ translate('Send') }}</button>

                            </div>
                            <div class="col-md-2"></div>
                        </div>

                    </div>
                    <br>
                    <br>
                </div>

                <div class="container text-center otp-phone-container" style="direction: ltr">
                    <h6 id="bloked_phone_message" class="text-center" style="color: red">
                        {{ translate('This phone is blocked please change it.') }}
                    </h6>
                    <h6 id="incorrect_phone_message" class="text-center" style="color: red">
                        {{ translate('This code is incorrect.') }}
                    </h6>
                    <form method="get" class="digit-group" data-group-name="digits" data-autosubmit="false"
                        autocomplete="off">
                        <input type="text" id="digit-1" name="digit-1" data-next="digit-2" />
                        <input type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" />
                        <input type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" />
                        <input type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" />
                    </form>
                    <br>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8" style="text-align: center">

                            <button type="button" id="send-phone-verification-code-button" onclick="sendVerificationCode()"
                                class="btn btn-base-1">{{ translate('Re-send code.') }}</button>
                            <button type="button" id="verify-my-phone-button" onclick="verifiyMyPhone()"
                                class="btn btn-base-1">{{ translate('Verify Now') }}</button>
                            <button type="button" id="go-back-if-blocked" style="display: none" onclick="goBackIfBlocked()"
                                class="btn btn-base-1">{{ translate('Change number') }}</button>

                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    <br>
                </div>
                <div class="container complete-add-adress-container">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('addresses.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Address') }}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <textarea class="form-control textarea-autogrow mb-3"
                                                placeholder="{{ translate('Your Address') }}" rows="1" name="address"
                                                required></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Country') }}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="mb-3">
                                                <select class="form-control mb-3  selectpicker"
                                                    onchange="get_provinces(this)"
                                                    data-placeholder="{{ translate('Select your country') }}"
                                                    id="country_select" name="country" required>
                                                    @foreach (\App\Country::where('status', 1)->select(['*', 'name_' . locale() . ' as name'])->get() as $key => $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Provinces') }}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <select class="form-control mb-3 selectpicker" onchange="get_cities(this)"
                                                data-placeholder="{{ translate('Your Province') }}" id="Province_select"
                                                name="province" required>

                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('City') }}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <select class="form-control mb-3 selectpicker" onchange="get_regions(this)"
                                                data-placeholder="{{ translate('Your City') }}" id="city_select" name="city"
                                                required>

                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Region') }}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <select class="form-control mb-3 selectpicker"
                                                data-placeholder="{{ translate('Select Your Region') }}" id="region_select"
                                                name="region" required>

                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row disabled-phone">
                                        <div class="col-md-2">
                                            <label>{{ translate('Phone') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control mb-3" id="addressPhone-disabled"
                                                placeholder="{{ translate('+880') }}" value="" disabled>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="button" onclick="editCurrentPhone()" class="btn btn-base-1"
                                                value="{{ translate('Edit') }}">
                                        </div>
                                    </div>
                                    <div class="enabled-phone">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label>{{ translate('Phone') }}</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control mb-3" id="addressPhone-hidden"
                                                    placeholder="{{ translate('+880') }}" name="phone" value="">
                                            </div>

                                            <div class="col-md">
                                                <input id="addressPhone-hidden-button" type="button"
                                                    onclick="sendVerificationCode()" class="btn btn-base-1"
                                                    value="{{ translate('Verify') }}">


                                            </div>


                                        </div>
                                    </div>


                                </div>

                            </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="back-new-adress" class="btn btn-base-1">{{ translate('Back') }}</button>
                    <button type="submit" id="submit-new-adress" class="btn btn-base-1">{{ translate('Save') }}</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    </div>

    <div class="col-xl-4 ml-lg-auto">
        @include('frontend.partials.cart_summary')
    </div>
    </div>
@else
    <div class="dc-header">
        <h3 class="heading heading-6 strong-700">{{ translate('Your Cart is empty') }}</h3>
    </div>
    @endif
    </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="GuestCheckout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ translate('Login') }}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                            @csrf
                            @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                <span>{{ translate('Use country code before number') }}</span>
                            @endif
                            <div class="form-group">
                                @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                    <input type="text"
                                        class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        value="{{ old('email') }}" placeholder="{{ translate('Email Or Phone') }}"
                                        name="email" id="email">
                                @else
                                    <input type="email"
                                        class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email">
                                @endif
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control h-auto form-control-lg"
                                    placeholder="{{ translate('Password') }}">
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <a href="{{ route('password.request') }}"
                                        class="link link-xs link--style-3">{{ translate('Forgot password?') }}</a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit"
                                        class="btn btn-styled btn-base-1 px-4">{{ translate('Sign in') }}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="text-center pt-3">
                        <p class="text-md">
                            {{ translate('Need an account?') }} <a href="{{ route('user.registration') }}"
                                class="strong-600">{{ translate('Register Now') }}</a>
                        </p>
                    </div>
                    @if (\App\BusinessSetting::where('type', 'google_login')->first()->value == 1 || \App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1 || \App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
                        <div class="or or--1 my-3 text-center">
                            <span>{{ translate('or') }}</span>
                        </div>
                        <div class="p-3 pb-0">
                            @if (\App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1)
                                <a href="{{ route('social.login', ['provider' => 'facebook']) }}"
                                    class="btn btn-styled btn-block btn-facebook btn-icon--2 btn-icon-left px-4 mb-3">
                                    <i class="icon fa fa-facebook"></i> {{ translate('Login with Facebook') }}
                                </a>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'google_login')->first()->value == 1)
                                <a href="{{ route('social.login', ['provider' => 'google']) }}"
                                    class="btn btn-styled btn-block btn-google btn-icon--2 btn-icon-left px-4 mb-3">
                                    <i class="icon fa fa-google"></i> {{ translate('Login with Google') }}
                                </a>
                            @endif
                            @if (\App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
                                <a href="{{ route('social.login', ['provider' => 'twitter']) }}"
                                    class="btn btn-styled btn-block btn-twitter btn-icon--2 btn-icon-left px-4 mb-3">
                                    <i class="icon fa fa-twitter"></i> {{ translate('Login with Twitter') }}
                                </a>
                            @endif
                        </div>
                    @endif
                    @if (\App\BusinessSetting::where('type', 'guest_checkout_active')->first()->value == 1)
                        <div class="or or--1 mt-0 text-center">
                            <span>{{ translate('or') }}</span>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('checkout.shipping_info') }}"
                                class="btn btn-styled btn-base-1">{{ translate('Guest Checkout') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--Shipping Info page-->

@endsection

@section('script')
    <script>
        var sendSmsCountDown = 59;
        var default_phone = `{{ $default_phone }}`;
        $('#verify-my-phone-button').prop("disabled", true);
        $('#incorrect_phone_message').hide();
        if (default_phone == false) {
            $('#submit-new-adress').prop("disabled", true);
            $('.complete-add-adress-container ,.new-phone-container , .otp-phone-container,.existing-new-phone-container,#add-adress-title,.existing-phone-container')
                .hide();
            $('.disabled-phone').hide();
            $('.enabled-phone').show();
            $('.complete-add-adress-container').show();
        } else {
            $('#addressPhone-disabled').val(default_phone);
            $('#addressPhone-hidden').val(default_phone);
            $('.complete-add-adress-container ,.new-phone-container , .otp-phone-container,.existing-new-phone-container,#add-adress-title,.existing-phone-container')
                .hide();
            $('.complete-add-adress-container').show();
        }

        function editCurrentPhone() {
            $('.disabled-phone').hide();
            $('.enabled-phone').show();
            $('#submit-new-adress').prop("disabled", true);
        }
        $('#new-phone-button').click(function() {
            $('.loading-address-img').show();
            $('.complete-add-adress-container ,.new-phone-container , .otp-phone-container,.existing-new-phone-container,#add-adress-title,.existing-phone-container')
                .hide();
            setTimeout(() => {
                $('.loading-address-img').hide();

                $('.new-phone-container').show();
            }, 500);

        })

        function goBackIfBlocked() {
            $('#addressPhone-disabled').val('');
            $('#addressPhone-hidden').val('');
            $('.complete-add-adress-container ,.new-phone-container , .otp-phone-container,.existing-new-phone-container,#add-adress-title,.existing-phone-container')
                .hide();
            $('.complete-add-adress-container').show();
            $('#send-phone-verification-code-button').show();
            $('#verify-my-phone-button').show();
            $('#bloked_phone_message').hide();
            $('#go-back-if-blocked').hide();
        }

        $('#back-new-adress').click(function(){
            $('.complete-add-adress-container ,.new-phone-container , .otp-phone-container,.existing-new-phone-container,#add-adress-title,.existing-phone-container')
                .hide();
            $('.complete-add-adress-container').show();
            $('#send-phone-verification-code-button').show();
            $('#verify-my-phone-button').show();
            $('#bloked_phone_message').hide();
            $('#go-back-if-blocked').hide();
        });

        function sendVerificationCode() {
            $('#submit-new-adress').prop("disabled", true);
            $('.complete-add-adress-container ,.new-phone-container , .otp-phone-container,.existing-new-phone-container,#add-adress-title,.existing-phone-container')
                .hide();
            $('.loading-address-img').show();
            var phone = $('#addressPhone').val();
            if (phone != '' || $('#addressPhone-hidden').val() != '') {
                if (phone == '') {
                    phone = $('#addressPhone-hidden').val();
                }
                $.post(`{{ route('cart.verifyAdressPhone') }}`, {
                    _token: '{{ csrf_token() }}',
                    phone: phone
                }, function(data) {
                    if (data.success == true) {
                        $('#digit-1').prop("disabled", false);
                        $('#digit-2').prop("disabled", false);
                        $('#digit-3').prop("disabled", false);
                        $('#digit-4').prop("disabled", false);
                        timerr = setInterval(() => {
                            sendSmsCountDown--;
                            $('#send-phone-verification-code-button').text(sendSmsCountDown);
                            $('#send-phone-verification-code-button').prop("disabled", true);
                            if (sendSmsCountDown <= 0) {
                                $('#send-phone-verification-code-button').prop("disabled", false);
                                $('#send-phone-verification-code-button').text(
                                    `{{ translate('Re-send code.') }}`);
                                clearInterval(timerr);
                                sendSmsCountDown = 59;
                            }

                        }, 1000);
                        $('#addressPhone-disabled').val(phone);
                        $('#addressPhone-hidden').val(phone);
                        $('.disabled-phone').show();
                        $('.enabled-phone').hide();
                        setTimeout(() => {
                            $('.loading-address-img').hide();

                            $('.otp-phone-container').show();
                        }, 500);
                    } else {
                        if (data.status == 'blocked') {
                            $('#addressPhone-disabled').val('');
                            $('#addressPhone-hidden').val('');
                            $('#digit-1').prop("disabled", true);
                            $('#digit-2').prop("disabled", true);
                            $('#digit-3').prop("disabled", true);
                            $('#digit-4').prop("disabled", true);
                            $('#bloked_phone_message').show();
                            setTimeout(() => {
                                $('#bloked_phone_message').hide();
                            }, 4000);
                            $('.otp-phone-container').show();

                            $('.loading-address-img').hide();
                            $('#send-phone-verification-code-button').hide();
                            $('#verify-my-phone-button').hide();
                            $('#go-back-if-blocked').show();
                        } else if (data.status == 'active') {
                            $('.loading-address-img').hide();
                            $('.existing-phone-container').show();
                        }

                    }
                    console.log(data);
                });

            } else {
                $('.complete-add-adress-container').show();
                $('.loading-address-img').hide();
                alert(`{{ translate('Please fill the phone') }}`)
            }

        }

        function verifiyMyPhone() {
            $('.complete-add-adress-container ,.new-phone-container , .otp-phone-container,.existing-new-phone-container,#add-adress-title,.existing-phone-container')
                .hide();
            $('.loading-address-img').show();
            var phone = $('#addressPhone').val();
            if (phone == '') {
                phone = $('#addressPhone-hidden').val();
            }
            var vCode = $('#digit-1').val() + $('#digit-2').val() + $('#digit-3').val() + $('#digit-4').val();
            if ($('#digit-1').val() != '' && $('#digit-2').val() != '' && $('#digit-3').val() != '' && $('#digit-4')
                .val() != '') {
                $.post(`{{ route('cart.verifyAdressPhoneAndGetResult') }}`, {
                    _token: '{{ csrf_token() }}',
                    phone: phone,
                    code: vCode
                }, function(data) {
                    console.log(data)
                    if (data.success == true) {
                        
                        $('#exist_phones').append(`<option value="${phone}">${phone}</option>`);
                        $('#submit-new-adress').prop("disabled", false);
                        $('#addressPhone-disabled').val(phone);
                        $('#addressPhone-hidden').val(phone);
                        setTimeout(() => {
                            $('.loading-address-img').hide();

                            $('.complete-add-adress-container').show();
                        }, 500);

                    } else {
                        if (data.status == 'blocked') {
                            $('#addressPhone-disabled').val('');
                            $('#addressPhone-hidden').val('');
                            $('#verify-my-phone-button').prop("disabled", true);
                            $('#digit-1').prop("disabled", true);
                            $('#digit-2').prop("disabled", true);
                            $('#digit-3').prop("disabled", true);
                            $('#digit-4').prop("disabled", true);
                            $('#bloked_phone_message').show();
                            setTimeout(() => {
                                $('#bloked_phone_message').hide();
                            }, 4000);
                            $('.loading-address-img').hide();
                            $('.otp-phone-container').show();
                            $('#send-phone-verification-code-button').hide();
                            $('#verify-my-phone-button').hide();
                            $('#go-back-if-blocked').show();
                        } else {
                            $('#verify-my-phone-button').prop("disabled", true);
                            $('.loading-address-img').hide();
                            $('.otp-phone-container').show();
                            $('#incorrect_phone_message').show();
                            setTimeout(() => {
                                $('#incorrect_phone_message').hide();
                            }, 6000);
                        }


                    }
                    $('#digit-1').val('');
                    $('#digit-2').val('');
                    $('#digit-3').val('');
                    $('#digit-4').val('');
                });
            } else {
                $('.loading-address-img').hide();
                $('.otp-phone-container').show();
                $('#verify-my-phone-button').prop("disabled", true);
            }

        }
        $('#existing-phone-button').click(function() {
            $('.complete-add-adress-container ,.new-phone-container , .otp-phone-container,.existing-new-phone-container,#add-adress-title,.existing-phone-container')
                .hide();
            $('.existing-phone-container').show();
        })

        function setInputPhone(el) {
            $('#submit-new-adress').prop("disabled", false);
            $('#addressPhone-disabled').val(el.value);
            $('#addressPhone-hidden').val(el.value);
            $('.disabled-phone').show();
            $('.enabled-phone').hide();
            $('.complete-add-adress-container ,.new-phone-container , .otp-phone-container,.existing-new-phone-container,#add-adress-title,.existing-phone-container')
                .hide();
            $('.complete-add-adress-container').show();
            $("#empty_select_option").prop("selected", true)
        }

    </script>
    
    <script type="text/javascript">
        function add_new_address() {
            $('#new-address-modal').modal('show');
        }

        var f_country_id = $('#country_select').children(":selected").attr("value");
        $.post(`{{ route('countries.get_provinces') }}`, {
            _token: '{{ csrf_token() }}',
            id: f_country_id
        }, function(data) {
            $("#Province_select").html('')
            $("#city_select").html('')
            $("#region_select").html('')
            $.each(data, function(index, value) {
                $("#Province_select").append("<option value='" + value.id +
                    "'>" + value.name + "</option>")
            })

            $.post(`{{ route('countries.get_cities') }}`, {
                    _token: '{{ csrf_token() }}',
                    id: data[0].id
                },
                function(data2) {
                    $("#city_select").html('');
                    $.each(data2, function(index, value) {
                        $("#city_select").append("<option value='" + value.id +
                            "'>" + value.name + "</option>")
                    })
                    $.post(`{{ route('countries.get_regions') }}`, {
                            _token: '{{ csrf_token() }}',
                            id: data2[0].id
                        },
                        function(data3) {
                            $("#region_select").html('');
                            console.log(data3);
                            $.each(data3, function(index, value) {
                                $("#region_select").append("<option value='" + value.id +
                                    "'>" + value.name + "</option>")
                            })
                        });
                });
        });

        function get_provinces(el) {
            $.post(`{{ route('countries.get_provinces') }}`, {
                _token: '{{ csrf_token() }}',
                id: el.value
            }, function(data) {
                $("#Province_select").html('')
                $("#city_select").html('')
                $("#region_select").html('')
                $.each(data, function(index, value) {
                    $("#Province_select").append("<option value='" + value.id +
                        "'>" + value.name + "</option>")
                })
                $.post(`{{ route('countries.get_cities') }}`, {
                        _token: '{{ csrf_token() }}',
                        id: data[0].id
                    },
                    function(data2) {
                        $("#city_select").html('');
                        $.each(data2, function(index, value) {
                            $("#city_select").append("<option value='" + value.id +
                                "'>" + value.name + "</option>")
                        })
                        $.post(`{{ route('countries.get_regions') }}`, {
                                _token: '{{ csrf_token() }}',
                                id: data2[0].id
                            },
                            function(data3) {
                                $("#region_select").html('');
                                console.log(data3);
                                $.each(data3, function(index, value) {
                                    $("#region_select").append("<option value='" + value.id +
                                        "'>" + value.name + "</option>")
                                })
                            });
                    });

            });
        }

        function get_cities(el) {
            $.post(`{{ route('countries.get_cities') }}`, {
                _token: '{{ csrf_token() }}',
                id: el.value
            }, function(data) {
                $("#city_select").html('')
                $("#region_select").html('')
                $.each(data, function(index, value) {
                    $("#city_select").append("<option value='" + value.id +
                        "'>" + value.name + "</option>")
                })
                $.post(`{{ route('countries.get_regions') }}`, {
                        _token: '{{ csrf_token() }}',
                        id: data[0].id
                    },
                    function(data2) {
                        $("#region_select").html('');
                        console.log(data2);
                        $.each(data2, function(index, value) {
                            $("#region_select").append("<option value='" + value.id +
                                "'>" + value.name + "</option>")
                        })
                    });
            });
        }

        function get_regions(el) {
            $.post(`{{ route('countries.get_regions') }}`, {
                _token: `{{ csrf_token() }}`,
                id: el.value
            }, function(data) {
                $("#region_select").html('');
                console.log(data);
                $.each(data, function(index, value) {
                    $("#region_select").append("<option value='" + value.id +
                        "'>" + value.name + "</option>")
                })
            });
        }

    </script>

    <script>
        $('.digit-group').find('input').each(function() {
            $(this).attr('maxlength', 1);
            $(this).on('keyup', function(e) {
                if ($('#digit-1').val() == '' || $('#digit-2').val() == '' || $('#digit-3').val() == '' ||
                    $('#digit-4').val() == '') {
                    $('#verify-my-phone-button').prop("disabled", true)
                } else {
                    $('#verify-my-phone-button').prop("disabled", false)
                }
                var parent = $($(this).parent());

                if (e.keyCode === 8 || e.keyCode === 37) {
                    var prev = parent.find('input#' + $(this).data('previous'));

                    if (prev.length) {
                        $(prev).select();
                    }
                } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <=
                        90) || (
                        e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                    var next = parent.find('input#' + $(this).data('next'));

                    if (next.length) {
                        $(next).select();
                    } else {
                        if (parent.data('autosubmit')) {
                            parent.submit();
                        }
                    }
                }
            });
        });

    </script>

    <script type="text/javascript">
        function updateQuantity(key, element) {
            $.post(`{{ route('cart.updateQuantity') }}`, {
                _token: '{{ csrf_token() }}',
                key: key,
                quantity: element.value
            }, function(data) {
                updateNavCart();
                location.reload();
                // $('#cart-summary').html(data);
            });
        }

        function showCheckoutModal() {
            $('#GuestCheckout').modal();
        }

    </script>

@endsection
