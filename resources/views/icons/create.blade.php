  <div class="aiz-titlebar text-left mt-2 mb-3">
      <h5 class="mb-0 h6">{{ translate('Payment Icon') }}</h5>
  </div>
  <form class="form-horizontal" action="{{ route('icons.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="form-group row">
          <label class="col-md-3 col-from-label">{{ translate('Title English') }}</label>
          <div class="col-md-8">
              <input type="text" class="form-control" name="title_en" placeholder="{{ translate('Title English') }}"
                  id="title_en" required>
          </div>
      </div>

      <div class="form-group row">
          <label class="col-md-3 col-from-label">{{ translate('Title Arabic') }}</label>
          <div class="col-md-8">
              <input type="text" class="form-control" name="title_ar" placeholder="{{ translate('Title Arabic') }}"
                  id="title_ar" required>
          </div>
      </div>

      <div class="form-group row">
          <label class="col-md-3 col-from-label">{{ translate('Link') }}</label>
          <div class="col-md-8">
              <input type="text" class="form-control" name="link" placeholder="{{ translate('Link') }}" id="link"
                  >
          </div>
      </div>

      <div class="form-group row">
          <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Icon') }}
          </label>
          <div class="col-md-8">
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
      <div class="mb-3 text-right">
          <button type="submit" name="button" class="btn btn-primary">{{ translate('Save') }}</button>
      </div>
  </form>
