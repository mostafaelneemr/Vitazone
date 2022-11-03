    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Home Categories') }}</h5>
    </div>

    <form class="form-horizontal" action="{{ route('home_categories.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{ translate('Category') }}</label>
            <div class="col-md-9">
                <select class="select2 form-control aiz-selectpicker" name="category_id" data-toggle="select2"
                    data-placeholder="Choose ..." data-live-search="true">
                    @foreach (\App\Category::where('level', 0)
        ->where('published',true)->select(['*', 'name_' . locale() . ' as name'])
        ->get()
    as $category)
                        @if (\App\HomeCategory::where('category_id', $category->id)->first() == null)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3 text-right">
            <button type="submit" name="button" class="btn btn-primary">{{ translate('Save') }}</button>
        </div>
    </form>
