@extends('layouts.app')

@section('content')

    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Fire Notification') }}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('newsletters.sendFirNotification') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="link">{{ translate('Link to go') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="link" id="link" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md col-lg col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-from-label"
                                    for="subject">{{ translate('Notification arabic banner') }}</label>
                                <div class="col-sm-10">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="banner_ar" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>

                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-from-label"
                                    for="subject">{{ translate('Arabic title') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="subject_ar" id="subject" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-from-label"
                                    for="name">{{ translate('Arabic content') }}</label>
                                <div class="col-sm-10">
                                    <textarea rows="8" class="form-control" name="content_ar" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md col-lg col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-from-label"
                                    for="subject">{{ translate('Notification english banner') }}</label>
                                <div class="col-sm-10">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="banner_en" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>

                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-from-label"
                                    for="subject">{{ translate('English title') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="subject_en" id="subject" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-from-label"
                                    for="name">{{ translate('English content') }}</label>
                                <div class="col-sm-10">
                                    <textarea rows="8" class="form-control" name="content_en" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Fire') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
