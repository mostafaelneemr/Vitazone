@extends('layouts.app')

@section('content')
    <form class="form-horizontal" action="{{ route('pages.update', $page->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" value="PATCH">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Edit Page') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Cover Photo') }}
                            <small>({{ translate('850px*420px') }})</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" value="{{ $page->cover_photo }}" name="cover_photo" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Cover Photo mobile') }}
                            <small>({{ translate('850px*420px') }})</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" value="{{ $page->cover_photo_mobile }}" name="cover_photo_mobile" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
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
                                <input type="hidden" name="icon" value="{{ $page->icon }}" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="title_en">{{ translate('Title English') }}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{ translate('Title English') }}" id="title_en"
                                name="title_en" class="form-control" value="{{ $page->title_en }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="title_ar">{{ translate('Title Arabic') }}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{ translate('Title Arabic') }}"
                                value="{{ $page->title_ar }}" id="title_ar" name="title_ar" class="form-control"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="slug_en">{{ translate('Slug English') }} <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <div class="input-group d-block d-md-flex">
                                <div class="input-group-prepend "><span
                                        class="input-group-text flex-grow-1">{{ route('home') }}/</span></div>
                                <input type="text" class="form-control w-100 w-md-auto"
                                    placeholder="{{ translate('Slug English') }}" name="slug_en"
                                    value="{{ $page->slug_en }}" required>
                            </div>
                            <small
                                class="form-text text-muted">{{ translate('Use character, number, hypen only') }}</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="slug_ar">{{ translate('Slug Arabic') }} <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <div class="input-group d-block d-md-flex">
                                <div class="input-group-prepend "><span
                                        class="input-group-text flex-grow-1">{{ route('home') }}/</span></div>
                                <input type="text" class="form-control w-100 w-md-auto"
                                    placeholder="{{ translate('Slug Arabic') }}" name="slug_ar"
                                    value="{{ $page->slug_ar }}" required>
                            </div>
                            <small
                                class="form-text text-muted">{{ translate('Use character, number, hypen only') }}</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="content_en">{{ translate('Content English') }} <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <textarea class="aiz-text-editor form-control"
                                data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                                placeholder="{{ translate('Content English') }}" data-min-height="300" name="content_en"
                                required>{!! $page->content_en !!}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="content_ar">{{ translate('Content Arabic') }} <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <textarea class="aiz-text-editor form-control"
                                data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                                placeholder="{{ translate('Content Arabic') }}" data-min-height="300" name="content_ar"
                                required>{!! $page->content_ar !!}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label"
                            for="meta_title_en">{{ translate('Meta Title English') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="{{ translate('Meta Title English') }}"
                                name="meta_title_en" value="{{ $page->meta_title_en }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label"
                            for="meta_title_ar">{{ translate('Meta Title Arabic') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="{{ translate('Meta Title Arabic') }}"
                                name="meta_title_ar" value="{{ $page->meta_title_ar }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label"
                            for="meta_description_en">{{ translate('Meta Description English') }}</label>
                        <div class="col-sm-10">
                            <textarea class="resize-off form-control"
                                placeholder="{{ translate('Meta Description English') }}"
                                name="meta_description_en">{{ $page->meta_description_en }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label"
                            for="meta_description_ar">{{ translate('Meta Description Arabic') }}</label>
                        <div class="col-sm-10">
                            <textarea class="resize-off form-control"
                                placeholder="{{ translate('Meta Description Arabic') }}"
                                name="meta_description_ar">{{ $page->meta_description_ar }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label"
                            for="keywords_en">{{ translate('Keywords English') }}</label>
                        <div class="col-sm-10">
                            <textarea class="resize-off form-control" placeholder="{{ translate('Keywords English') }}"
                                name="keywords_en">{{ $page->keywords_en }}</textarea>
                            <small class="text-muted">{{ translate('Separate with coma') }}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label"
                            for="keywords_ar">{{ translate('Keywords Arabic') }}</label>
                        <div class="col-sm-10">
                            <textarea class="resize-off form-control" placeholder="{{ translate('Keywords Arabic') }}"
                                name="keywords_ar">{{ $page->keywords_ar }}</textarea>
                            <small class="text-muted">{{ translate('Separate with coma') }}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="name">{{ translate('Meta Image') }}</label>
                        <div class="col-sm-10">
                            <div class="input-group " data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="meta_image" value="{{ $page->meta_image }}"
                                    class="selected-files">
                            </div>
                            <div class="file-preview">
                            </div>
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
            </div>
        </div>
    </form>
@endsection
