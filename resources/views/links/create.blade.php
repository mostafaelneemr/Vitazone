@extends('layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Useful Link')}}</h5>
</div>

<div class="col-lg-6 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Useful Link')}}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('links.store') }}" method="POST" enctype="multipart/form-data">
            	@csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name_en">{{translate('Name English')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Name English')}}" id="name_en" name="name_en" class="form-control" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name_ar">{{translate('Name Arabic')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Name Arabic')}}" id="name_ar" name="name_ar" class="form-control" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="link_en">{{translate('Link English')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Link English')}}" id="link_en" name="link_en" class="form-control" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="link_ar">{{translate('Link Arabic')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Link Arabic')}}" id="link_ar" name="link_ar" class="form-control" required>
                    </div>
                </div>

                
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


