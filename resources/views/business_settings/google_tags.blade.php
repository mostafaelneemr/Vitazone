@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Google Analytics Setting') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('google_tags.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{ translate('Google Analytics') }}</label>
                            </div>
                            <div class="col-md-7">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="google_tag" type="checkbox" @if (\App\BusinessSetting::where('type', 'google_tag')->first()->value == 1) checked @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="google_tag_script">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{ translate('Script') }}</label>
                            </div>
                            <textarea name="google_tag_script" rows="8" class="form-control" placeholder="{{ translate('Script') }}" id="google_tag_script">{{ \App\BusinessSetting::where('type', 'google_tag_script')->first()->value }}</textarea>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="google_tag_tag">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{ translate('Tag') }}</label>
                            </div>
                            <textarea name="google_tag_tag" rows="8" class="form-control" placeholder="{{ translate('Tag') }}" id="google_tag_tag">{{ \App\BusinessSetting::where('type', 'google_tag_tag')->first()->value }}</textarea>

                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
@endsection
