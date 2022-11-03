@extends('layouts.app')

@section('content')

    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6">{{ translate('Coupon Information Update') }}</h3>
            </div>
            <form action="{{ route('coupon.update', $coupon->id) }}" method="POST">
                <input name="_method" type="hidden" value="PATCH">
                @csrf
                <div class="card-body">
                    <input type="hidden" name="id" value="{{ $coupon->id }}" id="id">
                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label" for="name">{{ translate('Coupon Type') }}</label>
                        <div class="col-lg-9">
                            <select name="coupon_type" id="coupon_type" class="form-control aiz-selectpicker"
                                onchange="coupon_form()" required>
                                @if ($coupon->type == 'product_base'))
                                    <option value="product_base" selected>{{ translate('For Products') }}</option>
                                @elseif ($coupon->type == "category_base"))
                                    <option value="category_base" selected>{{ translate('For Category') }}</option>
                                @elseif ($coupon->type == "cart_base")
                                    <option value="cart_base">{{ translate('For Total Orders') }}</option>
                                @elseif ($coupon->type == "vendor_base")
                                    <option value="vendor_base">{{ translate('For Specific Vendors') }}</option>
                                @elseif ($coupon->type == "user_base")
                                    <option value="user_base">{{ translate('For Specific Users') }}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div id="coupon_form">

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


@endsection
@section('script')

    <script type="text/javascript">
        function coupon_form() {
            var coupon_type = $('#coupon_type').val();
            var id = $('#id').val();
            $.post('{{ route('coupon.get_coupon_form_edit') }}', {
                _token: '{{ csrf_token() }}',
                coupon_type: coupon_type,
                id: id
            }, function(data) {
                $('#coupon_form').html(data);

                //    $('#demo-dp-range .input-daterange').datepicker({
                //        startDate: '-0d',
                //        todayBtn: "linked",
                //        autoclose: true,
                //        todayHighlight: true
                // });
            });
        }

        $(document).ready(function() {
            coupon_form();
        });

    </script>

@endsection
