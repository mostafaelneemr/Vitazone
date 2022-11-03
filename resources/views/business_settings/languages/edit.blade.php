@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Language Info') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('languages.update', $language->id) }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Name Arabic') }}</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('Name Arabic') }}" value="{{ $language->name_ar }}" id="name_ar"
                                    name="name_ar" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Name English') }}</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('Name English') }}" value="{{ $language->name_en }}" id="name_en"
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
                                        <option value="{{ pathinfo($path)['filename'] }}" @if ($language->code == pathinfo($path)['filename']) selected @endif
                                            data-content="<div class=''><img src='{{ my_asset('frontend/images/icons/flags/' . pathinfo($path)['filename'] . '.png') }}' class='mr-2'><span>{{ strtoupper(pathinfo($path)['filename']) }}</span></div>">
                                        </option>
                                    @endforeach
                                </select>
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
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
