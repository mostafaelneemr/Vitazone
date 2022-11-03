@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Blog Category Information')}}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="POST" action="{{ route('blog-category.store') }}">
                	@csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Name Arabic')}}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Name Arabic')}}" id="category_name" name="category_name_ar" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Name English')}}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Name English')}}" id="category_name" name="category_name_en" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">
                            {{translate('Save')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
