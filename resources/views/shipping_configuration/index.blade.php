@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Select Shipping Method')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('shipping_configuration.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="shipping_type">
                    <div class="radio mar-btm">
                        <input id="product-shipping" class="magic-radio" type="radio" name="shipping_type" value="product_wise_shipping" <?php if(\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'product_wise_shipping') echo "checked";?>>
                        <label for="product-shipping">
                            <span>{{translate('Product Wise Shipping Cost')}}</span>
                            <span></span>
                        </label>
                    </div>
                    <div class="radio mar-btm">
                        <input id="flat-shipping" class="magic-radio" type="radio" name="shipping_type" value="flat_rate" <?php if(\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'flat_rate') echo "checked";?>>
                        <label for="flat-shipping">{{translate('Flat Rate Shipping Cost')}}</label>
                    </div>
                    <div class="radio mar-btm">
                        <input id="seller-shipping" class="magic-radio" type="radio" name="shipping_type" value="seller_wise_shipping" <?php if(\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'seller_wise_shipping') echo "checked";?>>
                        <label for="seller-shipping">{{translate('Seller Wise Flat Shipping Cost')}}</label>
                    </div>
                    <div class="radio mar-btm">
                        <input id="area-shipping" class="magic-radio" type="radio" name="shipping_type" value="area_wise_shipping" <?php if(\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'area_wise_shipping') echo "checked";?>>
                        <label for="area-shipping">{{translate('Area Wise Flat Shipping Cost')}}</label>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Note')}}</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        1. {{ translate('Product Wise Shipping Cost calulation: Shipping cost is calculate by addition of each product shipping cost') }}.
                    </li>
                    <li class="list-group-item">
                        2. {{ translate('Flat Rate Shipping Cost calulation: How many products a customer purchase, doesn\'t matter. Shipping cost is fixed') }}.
                    </li>
                    <li class="list-group-item">
                        3. {{ translate('Seller Wise Flat Shipping Cost calulation: Fixed rate for each seller. If customers purchase 2 product from two seller shipping cost is calculated by addition of each seller flat shipping cost') }}.
                    </li>
                    
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Flat Rate Cost')}}</h5>
            </div>
            <form action="{{ route('shipping_configuration.update') }}" method="POST" enctype="multipart/form-data">
              <div class="card-body">
                  @csrf
                  <input type="hidden" name="type" value="flat_rate_shipping_cost">
                  <div class="form-group">
                      <div class="col-lg-12">
                          <input class="form-control" type="text" name="flat_rate_shipping_cost" value="{{ \App\BusinessSetting::where('type', 'flat_rate_shipping_cost')->first()->value }}">
                      </div>
                  </div>
                  <div class="form-group mb-0 text-right">
                      <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                  </div>
              </div>
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Note')}}</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        {{ translate('1. Flat rate shipping cost is applicable if Flat rate shipping is enabled.') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Shipping Cost for Admin Products')}}</h5>
            </div>
            <form action="{{ route('shipping_configuration.update') }}" method="POST" enctype="multipart/form-data">
              <div class="card-body">
                  @csrf
                  <input type="hidden" name="type" value="shipping_cost_admin">
                  <div class="form-group">
                      <div class="col-lg-12">
                          <input class="form-control" type="text" name="shipping_cost_admin" value="{{ \App\BusinessSetting::where('type', 'shipping_cost_admin')->first()->value }}">
                      </div>
                  </div>
                  <div class="form-group mb-0 text-right">
                      <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                  </div>
              </div>
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Note')}}</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        {{ translate('1. Shipping cost for admin is applicable if Seller wise shipping cost is enabled.') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Free Shipping Treshold')}}</h5>
            </div>
            <form action="{{ route('shipping_configuration.update') }}" method="POST" enctype="multipart/form-data">
              <div class="card-body">
                  @csrf
                  <input type="hidden" name="type" value="free_shipping_treshold">
                  <div class="form-group">
                      <div class="col-lg-12">
                          <input class="form-control" type="text" name="free_shipping_treshold" value="{{ \App\BusinessSetting::where('type', 'free_shipping_treshold')->first()->value }}">
                      </div>
                  </div>
                  <div class="form-group mb-0 text-right">
                      <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                  </div>
              </div>
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Note')}}</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        {{ translate('1. Free shipping applicable if Flat Rate Shipping Cost enabled.') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Free Shipping By Area')}}</h5>
            </div>
            <form action="{{ route('shipping_configuration.update') }}" method="POST" enctype="multipart/form-data">
              <div class="card-body">
                  @csrf
                  <input type="hidden" name="type" value="free_shipping_by_city">
                  <div class="form-group">
                    <select name="free_shipping_by_city[]" class="form-control product_id aiz-selectpicker" data-live-search="true" data-selected-text-format="count" required multiple>
                        <option value="">{{ ('Select HashTags') }}</option>
                                @foreach (\App\Region::select(['*','name_'.locale().' as name'])->orderBy('created_at', 'desc')->get() as $region)
                                    <option value="{{ $region->id }}" @if(in_array($region->id, explode("," , \App\BusinessSetting::where('type', 'free_shipping_by_city')->first()->value))) selected @endif>{{ $region->name }}</option>
                                @endforeach
                    </select>
                  </div>
                  <div class="form-group mb-0 text-right">
                      <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                  </div>
              </div>
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Note')}}</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        {{ translate('1. Free shipping applicable if Flat Rate Shipping Cost enabled.') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
