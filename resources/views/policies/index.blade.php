@extends('layouts.app')

@section('content')

<div class="col-sm-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{ ucfirst(str_replace('_', ' ',$policy->name))}}</h3>
        </div>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('policies.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <input type="hidden" name="name" value="{{ $policy->name }}">
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="content_ar">{{translate('Content Ar')}}</label>
                    <div class="col-sm-10">
                        <textarea class="editor" name="content_ar" required>{{$policy->content_ar}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="content_en">{{translate('Content En')}}</label>
                    <div class="col-sm-10">
                        <textarea class="editor" name="content_en" required>{{$policy->content_en}}</textarea>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" type="submit">{{translate('Save')}}</button>
            </div>
        </form>

        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

@endsection
