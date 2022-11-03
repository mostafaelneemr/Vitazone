@extends('layouts.app')

@section('content')
    <form class="form-horizontal" action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Category Arabic Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">{{ translate('Name') }}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{ translate('Name Ar') }}" id="name" name="name_ar"
                                class="form-control"  onkeyup="makeSlugar(this.value)" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Arabic Slug') }}
                            <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{ translate('Arabic Slug') }}" name="slug_ar"
                                id="slug_ar" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Parent Category') }}</label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="parent_id" data-toggle="select2"
                                data-placeholder="Choose ..." data-live-search="true">
                                <option value="0">{{ translate('No Parent') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->{'name_' . locale()} }}</option>
                                    @foreach ($category->childrenCategories as $childCategory)
                                        @include('categories.child_category', ['child_category' => $childCategory])
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">{{ translate('Title Ar') }}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{ translate('Title Ar') }}" id="name" name="title_ar"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">{{ translate('Min Description Ar') }}</label>
                        <div class="col-md-9">
                            <textarea type="text" placeholder="{{ translate('Min Description Ar') }}" id="name"
                                name="description_ar" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">{{ translate('Type') }}</label>
                        <div class="col-md-9">
                            <select name="digital" required class="form-control demo-select2-placeholder">
                                <option value="0">{{ translate('Physical') }}</option>
                                <option value="1">{{ translate('Digital') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Meta Title') }}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="meta_title_ar"
                                placeholder="{{ translate('Meta Title Ar') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Icon') }}
                            <small>({{ translate('32x32') }})</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="icon" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Description Ar') }}</label>
                        <div class="col-md-9">
                            <textarea name="meta_description_ar" rows="8" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Hash Tags') }}</label>
                        <div class="col-md-9">
                            <select class="form-control aiz-selectpicker" data-live-search="true" multiple
                                name="hash_tags[]" id="hash_tags">
                                <option value="">{{ 'Select HashTags' }}</option>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Category English Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">{{ translate('Name') }}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{ translate('Name En') }}" id="name" name="name_en"
                                class="form-control" onkeyup="makeSlugen(this.value)" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('English Slug') }}
                            <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{ translate('English Slug') }}" name="slug_en"
                                id="slug_en" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">{{ translate('Title En') }}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{ translate('Title En') }}" id="name" name="title_en"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">{{ translate('Min Description En') }}</label>
                        <div class="col-md-9">
                            <textarea type="text" placeholder="{{ translate('Min Description En') }}" id="name"
                                name="description_en" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Meta Title') }}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="meta_title_en"
                                placeholder="{{ translate('Meta Title En') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Banner') }}
                            <small>({{ translate('200x200') }})</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="banner" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Description En') }}</label>
                        <div class="col-md-9">
                            <textarea name="meta_description_en" rows="8" class="form-control"></textarea>
                        </div>
                    </div>
                    @if (\App\BusinessSetting::where('type', 'category_wise_commission')->first()->value == 1)
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Commission Rate') }}</label>
                            <div class="col-md-9 input-group">
                                <input type="number" lang="en" min="0" step="0.01"
                                    placeholder="{{ translate('Commission Rate') }}" id="commision_rate"
                                    name="commision_rate" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="panel-footer text-right">
                    <button class="btn btn-purple" type="submit">{{ translate('Save') }}</button>
                </div>
            </div>
        </div>
        </div>
    </form>

@endsection
@section('script')
    <script>
        function makeSlugar(val) {
            let str = val;
            let output = str.replace(/\s+/g, '-').toLowerCase();
            $('#slug_ar').val(output);
        }

        function makeSlugen(val) {
            let str = val;
            let output = str.replace(/\s+/g, '-').toLowerCase();
            $('#slug_en').val(output);
        }

    </script>
@endsection
