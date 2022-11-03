@extends('layouts.app')

@section('content')
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Add New City') }}</h5>
            </div>
            <form class="form-horizontal" action="{{ route('cities.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Province Name') }}</label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="province_id" data-toggle="select2"
                                data-placeholder="Choose ..." data-live-search="true">
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row row">
                        <label class="col-sm-3 col-from-label" for="name_ar">{{ translate('Name Ar') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Name Ar') }}" id="name_ar" name="name_ar"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row row">
                        <label class="col-sm-3 col-from-label" for="name_en">{{ translate('Name En') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Name En') }}" id="name_en" name="name_en"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row row">
                        <label class="col-sm-3 col-from-label" for="code">{{ translate('Code') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Code') }}" id="code" name="code"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
