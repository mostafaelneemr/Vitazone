<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{ translate('Nav links') }}</h5>
</div>
<form class="form-horizontal" action="{{ route('navlinks.update', $navlink->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="_method" value="PATCH">
    <div class="form-group row">
        <label class="col-md-3 col-from-label">{{ translate('Title English') }}</label>
        <div class="col-md-8">
            <input type="text" value="{{ $navlink->title_en }}" class="form-control" name="title_en"
                placeholder="{{ translate('Title English') }}" id="title_en" required>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-from-label">{{ translate('Title Arabic') }}</label>
        <div class="col-md-8">
            <input type="text" class="form-control" value="{{ $navlink->title_ar }}" name="title_ar"
                placeholder="{{ translate('Title Arabic') }}" id="title_ar" required>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-3 col-from-label">{{ translate('Link') }}</label>
        <div class="col-md-8">
            <input type="text" class="form-control" name="link" value="{{ $navlink->link }}" placeholder="{{ translate('Title Arabic') }}"
                id="link" required>
        </div>
    </div>
   
    <div class="mb-3 text-right">
        <button type="submit" name="button" class="btn btn-primary">{{ translate('Save') }}</button>
    </div>
</form>
