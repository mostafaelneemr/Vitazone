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
    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @if (Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
                    @endif
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{ translate('Manage Profile') }}
                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{ translate('Home') }}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{ translate('Dashboard') }}</a></li>
                                            <li class="active"><a
                                                    href="{{ route('profile') }}">{{ translate('Manage Profile') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('frontend.message')

                        <form class="" action="{{ route('customer.profile.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Basic info') }}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Your Name') }}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control mb-3"
                                                placeholder="{{ translate('Your Name') }}" name="name"
                                                value="{{ Auth::user()->name }}">

                                                @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                                @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Your Phone') }}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="number" class="form-control mb-3"
                                                placeholder="{{ translate('Your Phone') }}" name="phone" readonly
                                                value="{{ Auth::user()->phone }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Photo') }}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="photo" id="file-3"
                                                class="custom-input-file custom-input-file--4"
                                                data-multiple-caption="{count} files selected" accept="image/*" />
                                            <label for="file-3" class="mw-100 mb-3">
                                                <span></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{ translate('Choose image') }}
                                                </strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Your Password') }}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="password" class="form-control mb-3"
                                                placeholder="{{ translate('New Password') }}" name="new_password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Confirm Password') }}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="password" class="form-control mb-3"
                                                placeholder="{{ translate('Confirm Password') }}"
                                                name="confirm_password">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right mt-4">
                                <button type="submit"
                                    class="btn btn-styled btn-base-1">{{ translate('Update Profile') }}</button>
                            </div>

                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Addresses') }}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row gutters-10">
                                        @foreach (Auth::user()->addresses as $key => $address)
                                            <div class="col-lg-6">
                                                <div class="border p-3 pr-5 rounded mb-3 position-relative">
                                                    <div>
                                                        <span class="alpha-6">{{ translate('Address') }}:</span>
                                                        <span class="strong-600 ml-2">{{ $address->address }}</span>
                                                    </div>
                                                    {{-- <div> --}}
                                                    {{-- <span
                                                            class="alpha-6">{{ translate('Postal Code') }}:</span> --}}
                                                    {{-- <span
                                                            class="strong-600 ml-2">{{ $address->postal_code }}</span> --}}
                                                    {{-- </div> --}}
                                                    <div>
                                                        <span class="alpha-6">{{ translate('Region') }}:</span>
                                                        <span
                                                            class="strong-600 ml-2">{{ $address->addressRegion ? $address->addressRegion->{'name_' . locale()} : '' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('City') }}:</span>
                                                        <span
                                                            class="strong-600 ml-2">{{ $address->addressCity ? $address->addressCity->{'name_' . locale()} : '' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('Province') }}:</span>
                                                        <span
                                                            class="strong-600 ml-2">{{ $address->addressProvince ? $address->addressProvince->{'name_' . locale()} : '' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('Country') }}:</span>
                                                        <span
                                                            class="strong-600 ml-2">{{ $address->addressProvince ? $address->addressCountry->{'name_' . locale()} : '' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="alpha-6">{{ translate('Phone') }}:</span>
                                                        <span class="strong-600 ml-2">{{ $address->phone }}</span>
                                                    </div>
                                                    @if ($address->set_default)
                                                        <div class="position-absolute right-0 bottom-0 pr-2 pb-3">
                                                            <span
                                                                class="badge badge-primary bg-base-1">{{ translate('Default') }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="dropdown position-absolute right-0 top-0">
                                                        <button class="btn bg-gray px-2" type="button"
                                                            data-toggle="dropdown">
                                                            <i class="la la-ellipsis-v"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right"
                                                            aria-labelledby="dropdownMenuButton">
                                                            @if (!$address->set_default)
                                                                <a class="dropdown-item"
                                                                    href="{{ route('addresses.set_default', $address->id) }}">{{ translate('Make This Default') }}</a>
                                                            @endif
                                                            {{-- <a class="dropdown-item"
                                                                href="">Edit</a> --}}
                                                            <a class="dropdown-item"
                                                                href="{{ route('addresses.destroy', $address->id) }}">{{ translate('Delete') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="col-lg-6 mx-auto" onclick="add_new_address()">
                                            <div class="border p-3 rounded mb-3 c-pointer text-center bg-light">
                                                <i class="la la-plus la-2x"></i>
                                                <div class="alpha-7">{{ translate('Add New Address') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>

                        <form action="{{ route('user.change.email') }}" method="POST">
                            @csrf
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{ translate('Change your email') }}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{ translate('Your Email') }}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="input-group mb-3">
                                                <input type="email" class="form-control"
                                                    placeholder="{{ translate('Your Email') }}" name="email"
                                                    value="{{ Auth::user()->email }}" />
                                                <div class="input-group-append">
                                                    <button type="button"
                                                        class="btn btn-outline-secondary new-email-verification">
                                                        <span class="d-none loading">
                                                            <span class="spinner-border spinner-border-sm" role="status"
                                                                aria-hidden="true"></span>
                                                            Sending Email...
                                                        </span>
                                                        <span class="default">Verify</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <button class="btn btn-styled btn-base-1" type="submit">Update Email</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
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
                        <img id="phone-v-loading-2" style="width: 40%;"
                            src="{{ my_asset('frontend/images/loading.gif') }}">

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
                                    <select class="form-control mb-3 selectpicker" id="exist_phones"
                                        onchange="setInputPhone(this)"
                                        data-placeholder="{{ translate('Select your phone') }}">
                                        <option id="empty_select_option" selected disabled>
                                            {{ translate('Select Your Phone') }}</option>
                                        @if (Auth::check())
                                            @foreach (\App\Phone::where(['user_id' => Auth::user()->id, 'status' => 'actived'])->select('id', 'phone')->get()
        as $key => $phonee)
                                                <option value="{{ $phonee->phone }}">{{ $phonee->phone }}</option>
                                            @endforeach
                                        @endif
                                    </select>
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
                                    onclick="sendVerificationCode()"
                                    class="btn btn-base-1">{{ translate('Send') }}</button>

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
                                                    @foreach (\App\Country::where('status', 1)->select(['*', 'name_' . locale() . ' as name'])->get()
        as $key => $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}
                                                        </option>
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
                                                data-placeholder="{{ translate('Your City') }}" id="city_select"
                                                name="city" required>

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
                                                data-placeholder="{{ translate('Select Your Region') }}"
                                                id="region_select" name="region" required>

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
    {{-- <div class="modal fade" id="new-address-modal" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ translate('New Address') }}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            </div>
        </div>
    </div> --}}

@endsection

@section('script')
    <script type="text/javascript">
        $('.new-email-verification').on('click', function() {

            $(this).find('.loading').removeClass('d-none');

            $(this).find('.default').addClass('d-none');

            var email = $("input[name=email]").val();

            $.post('{{ route('user.new.verify') }}', {_token:'{{ csrf_token() }}', email: email}, function(data){

                data = JSON.parse(data);

                $('.default').removeClass('d-none');

                $('.loading').addClass('d-none');

                if(data.status == 2)

                    AIZ.plugins.notify('warning', data.message);

                else if(data.status == 1)

                    AIZ.plugins.notify('success', data.message);

                else

                    AIZ.plugins.notify('danger', data.message);

            });

        });
    </script>

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
            $('#go-back-if-blocked').hide();
            $('#bloked_phone_message').hide();
        }

        $('#back-new-adress').click(function() {
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
                            console.log(sendSmsCountDown)
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
                            $('#send-phone-verification-code-button').prop("disabled", true);
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

        $('.new-email-verification').on('click', function() {
            $(this).find('.loading').removeClass('d-none');
            $(this).find('.default').addClass('d-none');
            var email = $("input[name=email]").val();

            $.post(`{{ route('user.new.verify') }}`, {
                    _token: '{{ csrf_token() }}',
                    email: email
                },
                function(data) {
                    data = JSON.parse(data);
                    $('.default').removeClass('d-none');
                    $('.loading').addClass('d-none');
                    if (data.status == 2)
                        showFrontendAlert('warning', data.message);
                    else if (data.status == 1)
                        showFrontendAlert('success', data.message);
                    else
                        showFrontendAlert('danger', data.message);
                });
        });
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
@endsection
