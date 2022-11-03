@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Blog Category Information')}}</h5>
            </div>
            <div class="card-body">
                <form id="add_form" class="form-horizontal" action="{{ route('blog-category.update', $cateogry->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Name Arabic')}}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Name Arabic')}}" id="category_name_ar" name="category_name_ar" value="{{ $cateogry->category_name_ar }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Name English')}}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Name English')}}" id="category_name_en" name="category_name_en" value="{{ $cateogry->category_name_en }}" class="form-control" required>
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
