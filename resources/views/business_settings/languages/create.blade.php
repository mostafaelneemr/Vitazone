
@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Language Info') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('languages.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Name Arabic') }}</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('Name Arabic') }}" id="name_ar"
                                    name="name_ar" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Name English') }}</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('Name English') }}" id="name_en"
                                    name="name_en" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{ translate('Code') }}</label>
                            </div>
                            <div class="col-lg-9">
                                <select class="form-control aiz-selectpicker mb-2 mb-md-0" name="code"
                                    data-live-search="true">
                                    @foreach (\File::files(base_path('public/frontend/images/icons/flags')) as $path)
                                        <option value="{{ pathinfo($path)['filename'] }}"
                                            data-content="<div class=''><img src='{{ my_asset('frontend/images/icons/flags/' . pathinfo($path)['filename'] . '.png') }}' class='mr-2'><span>{{ strtoupper(pathinfo($path)['filename']) }}</span></div>">
                                        </option>
                                    @endforeach
                                </select>
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
