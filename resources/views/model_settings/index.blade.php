@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Model Settings') }}</h5>
                </div>

                <form class="form-horizontal" action="{{ route('modelsetting.update', $model->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name_en">{{ translate('Name English') }}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{ translate('Name English') }}" id="name_en" name="name_en"
                                    value="{{ $model->name_en }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name_ar">{{ translate('Name Arabic') }}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{ translate('Name Arabic') }}" id="name_ar" name="name_ar"
                                    value="{{ $model->name_ar }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label"
                                for="name">{{ translate('Description English') }}</label>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="4" name="description_en"
                                    required>{{ $model->description_en }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label"
                                for="name">{{ translate('Description Arabic') }}</label>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="4" name="description_ar"
                                    required>{{ $model->description_ar }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{ translate('Select Status') }}</label>
                            <div class="col-sm-9">
                                <select name="status" required class="form-control aiz-selectpicker">
                                    <option value="1" @if ($model->status == 1) selected @endif>{{ translate('Active') }}</option>
                                    <option value="0" @if ($model->status != 1) selected @endif>{{ translate('Not Active') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
