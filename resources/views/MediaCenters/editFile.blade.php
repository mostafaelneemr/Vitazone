@extends('layouts.app')

@section('content')
    <form class="form-horizontal" action="{{ route('MediaCenters.updateFile') }}" method="POST"
        enctype="multipart/form-data">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="panel container">
            <div class="panel-heading">
                @php
                    $ext = explode('.', $file);
                    $ext = $ext[sizeof($ext) - 1];
                @endphp
                <h3 class="panel-title">{{ translate('Change File') }}
                    <small style="color: red">({{ translate('Extension should be ') . $ext }})</small>

                </h3>
            </div>
            @csrf
            <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="File">{{ translate('File') }}
                    </label>
                    <div class="col-sm-10">
                        <input type="file" id="File" name="File" class="form-control">
                        <input type="hidden" id="old_file" name="old_file" value="{{ $file }}" class="form-control">
                    </div>
                </div>

                <div class="panel-footer text-right">
                    <button class="btn btn-purple" type="submit">{{ translate('Save') }}</button>
                </div>
            </div>
        </div>

    </form>
@endsection

@section('script')

@endsection
