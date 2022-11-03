@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Tags Arabic Information') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('tags.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Name Ar') }}</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('Name Ar') }}" id="name_ar" name="name_ar"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Name En') }}</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('Name En') }}" id="name_en" name="name_en"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="meta_title_ar">{{ translate('Meta Title Ar') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="meta_title_ar"
                                    placeholder="{{ translate('Meta Title Ar') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="meta_title_en">{{ translate('Meta Title En') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="meta_title_en"
                                    placeholder="{{ translate('Meta Title En') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="meta_description_en">{{ translate('Meta Description Ar') }}</label>
                            <div class="col-md-9">
                                <textarea name="meta_description_ar" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="meta_description_en">{{ translate('Meta Description En') }}</label>
                            <div class="col-md-9">
                                <textarea name="meta_description_en" rows="5" class="form-control"></textarea>
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
