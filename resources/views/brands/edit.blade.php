@extends('layouts.app')

@section('content')
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Add New Brand') }}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('brands.update', $brand->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @method('PATCH')
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group mb-3">
                        <label for="name_ar">{{ translate('Name Ar') }}</label>
                        <input type="text" placeholder="{{ translate('Name Ar') }}" value="{{ $brand->name_ar }}"
                            name="name_ar" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ translate('Arabic Slug') }}<span class="text-danger">*</span></label>
                            <input type="text" placeholder="{{ translate('Arabic Slug') }}" name="slug_ar"
                                id="slug_ar" value="{{ $brand->slug_ar }}" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="name_en">{{ translate('Name En') }}</label>
                        <input type="text" placeholder="{{ translate('Name En') }}" value="{{ $brand->name_en }}"
                            name="name_en" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>{{ translate('English Slug') }}<span class="text-danger">*</span></label>
                            <input type="text" placeholder="{{ translate('English Slug') }}" name="slug_en"
                                id="slug_en" value="{{ $brand->slug_en }}" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">{{ translate('Logo') }} <small>({{ translate('120x80') }})</small></label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">
                                    {{ translate('Browse') }}
                                </div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="logo" class="selected-files" value="{{ $brand->logo }}">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="meta_title_ar">{{ translate('Meta Title Ar') }}</label>
                        <input type="text" class="form-control" name="meta_title_ar" value="{{ $brand->meta_title_ar }}"
                            placeholder="{{ translate('Meta Title Ar') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="meta_title_en">{{ translate('Meta Title En') }}</label>
                        <input type="text" class="form-control" name="meta_title_en" value="{{ $brand->meta_title_en }}"
                            placeholder="{{ translate('Meta Title En') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="meta_description_en">{{ translate('Meta Description Ar') }}</label>
                        <textarea name="meta_description_ar" rows="5"
                            class="form-control">{{ $brand->meta_description_ar }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="meta_description_en">{{ translate('Meta Description En') }}</label>
                        <textarea name="meta_description_en" rows="5"
                            class="form-control">{{ $brand->meta_description_en }}</textarea>
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
@endsection

@section('script')
    <script>
      

    </script>
@endsection
