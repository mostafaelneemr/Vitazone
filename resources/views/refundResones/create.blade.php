@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('refundResones') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('refundResones.store') }}" method="POST"
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
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Arabic Resone') }}</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('Arabic Resone') }}" id="resone_ar"
                                    name="resone_ar" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('English Resone') }}</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('English Resone') }}" id="resone_en"
                                    name="resone_en" class="form-control" required>
                            </div>
                        </div>


                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
