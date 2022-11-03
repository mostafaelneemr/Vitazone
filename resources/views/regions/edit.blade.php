@extends('layouts.app')

@section('content')
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Edit Region') }}</h5>
            </div>
            <form class="form-horizontal" action="{{ route('regions.update', $region->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('City Name') }}</label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="city_id" data-toggle="select2"
                                data-placeholder="Choose ..." data-live-search="true">
                                @foreach ($cities as $city)
                                    <option {{ $region->city_id == $city->id ? 'selected' : '' }}
                                        value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row row">
                        <label class="col-sm-3 col-from-label" for="name_ar">{{ translate('Name Ar') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Name Ar') }}" value="{{ $region->name_ar }}"
                                id="name_ar" name="name_ar" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row row">
                        <label class="col-sm-3 col-from-label" for="name_en">{{ translate('Name En') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Name En') }}" value="{{ $region->name_en }}"
                                id="name_en" name="name_en" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row row">
                        <label class="col-sm-3 col-from-label" for="code">{{ translate('Code') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Code') }}" value="{{ $region->code }}"
                                id="code" name="code" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row row">
                        <label class="col-sm-3 col-from-label"
                            for="shipping_cost">{{ translate('Light shipping Cost') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Light shipping Cost') }}"
                                value="{{ $region->shipping_cost }}" id="shipping_cost" name="shipping_cost"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row row">
                        <label class="col-sm-3 col-from-label"
                            for="shipping_cost_high">{{ translate('Heavy shipping Cost') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Heavy shipping Cost') }}"
                                id="shipping_cost_high" name="shipping_cost_high" class="form-control"
                                value="{{ $region->shipping_cost_high }}" required>
                        </div>
                    </div>

                    <div class="form-group row row">
                        <label class="col-sm-3 col-from-label"
                            for="shipping_duration">{{ translate('Light shipping duration') }}</label>
                        <div class="col-sm-9">
                            <input type="number" placeholder="{{ translate('Light shipping duration') }}"
                                id="shipping_duration" name="shipping_duration" class="form-control"
                                value="{{ $region->shipping_duration }}" required>
                        </div>
                    </div>

                    <div class="form-group row row">
                        <label class="col-sm-3 col-from-label"
                            for="shipping_duration_high">{{ translate('Heavy shipping duration') }}</label>
                        <div class="col-sm-9">
                            <input type="number" placeholder="{{ translate('Heavy shipping duration') }}"
                                id="shipping_duration_high" name="shipping_duration_high" class="form-control"
                                value="{{ $region->shipping_duration_high }}" required>
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
                </div>
            </form>
        </div>
    </div>

@endsection
