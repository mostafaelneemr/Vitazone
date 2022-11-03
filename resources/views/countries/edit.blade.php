@extends('layouts.app')

@section('content')

    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Edit Country') }}</h5>
            </div>
            <form class="form-horizontal" action="{{ route('countries.update', $country->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <div class="form-group row row">
                        <label class="col-sm-3 col-from-label" for="name_ar">{{ translate('Name Ar') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Name Ar') }}" value="{{ $country->name_ar }}"
                                id="name_ar" name="name_ar" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row row">
                        <label class="col-sm-3 col-from-label" for="name_en">{{ translate('Name En') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Name En') }}" value="{{ $country->name_en }}"
                                id="name_en" name="name_en" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row row">
                        <label class="col-sm-3 col-from-label" for="code">{{ translate('Code') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Code') }}" value="{{ $country->code }}"
                                id="code" name="code" class="form-control" required>
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
