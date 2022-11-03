<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{ translate('Slider Information') }}</h5>
</div>
<form class="form form-horizontal mar-top" action="{{ route('sliders.store') }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <label class="col-md-3 col-from-label">{{ translate('URL English') }}</label>
        <div class="col-md-8">
            <input type="text" class="form-control" name="url_en" placeholder="{{ translate('URL English') }}"
                id="url" required>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-3 col-from-label">{{ translate('URL Arabic') }}</label>
        <div class="col-md-8">
            <input type="text" class="form-control" name="url_ar" placeholder="{{ translate('URL Arabic') }}" id="url"
                required>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-from-label">{{ translate('URL Mobile') }}</label>
        <div class="col-md-8">
            <input type="text" class="form-control" name="mobile_link" placeholder="{{ translate('URL Mobile') }}" id="mobile_link">
        </div>
    </div>
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Web Images') }}</h5>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-from-label">{{ translate('Slider Arabic Image') }}
            <small>(850px*315px)</small></label>
        <div class="col-md-8">
            <div class="input-group" data-toggle="aizuploader" data-type="image">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                        {{ translate('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                <input type="hidden" name="photo_web_ar" class="selected-files">
            </div>
            <div class="file-preview box sm">
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-from-label">{{ translate('Slider English Image') }}
            <small>(850px*315px)</small></label>
        <div class="col-md-8">
            <div class="input-group" data-toggle="aizuploader" data-type="image">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                        {{ translate('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                <input type="hidden" name="photo_web_en" class="selected-files">
            </div>
            <div class="file-preview box sm">
            </div>
        </div>
    </div>

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Mobile Images') }}</h5>
    </div>

    <div class="form-group row">
        <label class="col-md-3 col-from-label">{{ translate('Slider Arabic Image') }}
            <small>(850px*315px)</small></label>
        <div class="col-md-8">
            <div class="input-group" data-toggle="aizuploader" data-type="image">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                        {{ translate('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                <input type="hidden" name="photo_mobile_ar" class="selected-files">
            </div>
            <div class="file-preview box sm">
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-from-label">{{ translate('Slider English Image') }}
            <small>(850px*315px)</small></label>
        <div class="col-md-8">
            <div class="input-group" data-toggle="aizuploader" data-type="image">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                        {{ translate('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                <input type="hidden" name="photo_mobile_en" class="selected-files">
            </div>
            <div class="file-preview box sm">
            </div>
        </div>
    </div>

    <div class="mb-3 text-right">
        <button type="submit" name="button" class="btn btn-primary">{{ translate('Save') }}</button>
    </div>
</form>
