@extends('layouts.app')

@section('content')

<div class="col-lg-6">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{ translate('Attribute Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('attributes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{ translate('Name English')}}</label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="{{ translate('Name English')}}" id="name_en" name="name_en" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{ translate('Name Arabic')}}</label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="{{ translate('Name Arabic')}}" id="name_ar" name="name_ar" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" type="submit">{{ translate('Save')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

@endsection
