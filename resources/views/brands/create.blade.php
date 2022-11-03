@extends('layouts.app')

@section('content')
    <form class="form-horizontal" action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">{{translate('Brand Arabic Information')}}</h3>
                </div>

                <!--Horizontal Form-->
                <!--===================================================-->

                @csrf
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="name">{{translate('Name')}}</label>
                        <div class="col-sm-10">
                            <input type="text" placeholder="{{translate('Name Ar')}}" id="name" name="name_ar"
                                   class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{translate('Meta Title')}}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="meta_title_ar"
                                   placeholder="{{translate('Meta Title Ar')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{translate('Description ')}}</label>
                        <div class="col-sm-10">
                            <textarea name="meta_description_ar" rows="8" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="logo">{{translate('Logo')}}
                            <small>({{ translate('120x80') }})</small></label>
                        <div class="col-sm-10">
                            <input type="file" id="logo" name="logo" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">{{translate('Brand English Information')}}</h3>
                </div>

                <!--Horizontal Form-->
                <!--===================================================-->

                @csrf
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="name">{{translate('Name')}}</label>
                        <div class="col-sm-10">
                            <input type="text" placeholder="{{translate('Name En')}}" id="name" name="name_en"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{translate('Meta Title ')}}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="meta_title_en"
                                   placeholder="{{translate('Meta Title En')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{translate('Description')}}</label>
                        <div class="col-sm-10">
                            <textarea name="meta_description_en" rows="8" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <button class="btn btn-purple" type="submit">{{translate('Save')}}</button>
                </div>

                <!--===================================================-->
                <!--End Horizontal Form-->

            </div>
        </div>
    </form>
@endsection
