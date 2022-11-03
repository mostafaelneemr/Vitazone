<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{ translate('Banner Information') }}</h5>
</div>
<form class="form-horizontal" action="{{ route('home_banners.update', $banner->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="_method" value="PATCH">
    <div class="form-group row">
        <label class="col-md-3 col-from-label">{{ translate('URL English') }}</label>
        <div class="col-md-8">
            <input type="text" value="{{ $banner->url_en }}" class="form-control" name="url_en"
                placeholder="{{ translate('URL English') }}" id="url" required>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-from-label">{{ translate('URL Arabic') }}</label>
        <div class="col-md-8">
            <input type="text" class="form-control" value="{{ $banner->url_ar }}" name="url_ar"
                placeholder="{{ translate('URL Arabic') }}" id="url" required>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-from-label">{{ translate('URL Mobile') }}</label>
        <div class="col-md-8">
            <input type="text" class="form-control" name="url_mobile" value="{{ $banner->url_mobile }}" placeholder="{{ translate('URL Mobile') }}"
                id="url_mobile">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Banner English Image') }}
            <small>({{ translate('850px*420px') }})</small></label>
        <div class="col-md-9">
            <div class="input-group" data-toggle="aizuploader" data-type="image">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                        {{ translate('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                <input type="hidden" name="photo_en" class="selected-files" value="{{ $banner->photo_en }}">
            </div>
            <div class="file-preview box sm">
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Banner Arabic Image') }}
            <small>({{ translate('850px*420px') }})</small></label>
        <div class="col-md-9">
            <div class="input-group" data-toggle="aizuploader" data-type="image">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                        {{ translate('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                <input type="hidden" name="photo_ar" class="selected-files" value="{{ $banner->photo_ar }}">
            </div>
            <div class="file-preview box sm">
            </div>
        </div>
    </div>

    <div class="mb-3 text-right">
        <button type="submit" name="button" class="btn btn-primary">{{ translate('Save') }}</button>
    </div>
</form>
