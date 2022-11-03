@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Tags Arabic Information') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('tags.update', $tag->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Name Ar') }}</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('Name Ar') }}" value="{{ $tag->name_ar }}" id="name_ar" name="name_ar"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Name En') }}</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('Name En') }}" value="{{ $tag->name_en }}" id="name_en" name="name_en"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="meta_title_ar">{{ translate('Meta Title Ar') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="{{ $tag->meta_title_ar }}" name="meta_title_ar"
                                    placeholder="{{ translate('Meta Title Ar') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="meta_title_en">{{ translate('Meta Title En') }}</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $tag->meta_title_en }}" class="form-control" name="meta_title_en"
                                    placeholder="{{ translate('Meta Title En') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="meta_description_en">{{ translate('Meta Description Ar') }}</label>
                            <div class="col-md-9">
                                <textarea name="meta_description_ar" rows="5" class="form-control">{{ $tag->meta_description_ar }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="meta_description_en">{{ translate('Meta Description En') }}</label>
                            <div class="col-md-9">
                                <textarea name="meta_description_en" rows="5" class="form-control">{{ $tag->meta_description_en }}</textarea>
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
