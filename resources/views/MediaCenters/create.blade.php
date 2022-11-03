@extends('layouts.app')

@section('content')
    <form class="form-horizontal" action="{{ route('MediaCenters.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="container">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ translate('Image Information') }}</h3>
                </div>

                <div class="panel-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="alt_en">{{ translate('English Alt') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('English Alt') }}" id="alt_en" name="alt_en"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ translate('Arabic Alt') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="alt_ar"
                                placeholder="{{ translate('Arabic Alt') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="file_path">{{ translate('Photo') }}</label>
                        <div class="col-sm-9">
                            <input type="file" id="file_path" name="file_path" class="form-control">
                        </div>
                    </div>


                    <div class="panel-footer text-right">
                        <button class="btn btn-purple" type="submit">{{ translate('Save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
