@extends('layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Color Settings') }}</h1>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6 col-lg-offset-3">
            <div class="card">
                <form class="form-horizontal p-5" action="{{ route('generalsettings.color.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="card-body text-center" style="background:#e62e04;">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="frontend_color" value="default" @if (\App\GeneralSetting::first()->frontend_color == 'default') checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="card-body text-center" style="background:#1abc9c;">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="frontend_color" value="1" @if (\App\GeneralSetting::first()->frontend_color == '1') checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="card-body text-center" style="background:#3498db;">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="frontend_color" value="2" @if (\App\GeneralSetting::first()->frontend_color == '2') checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="card-body text-center" style="background:#72bf40;">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="frontend_color" value="3" @if (\App\GeneralSetting::first()->frontend_color == '3') checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="card-body text-center" style="background:#F79F1F;">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="frontend_color" value="4" @if (\App\GeneralSetting::first()->frontend_color == '4') checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="card-body text-center" style="background:#12CBC4;">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="frontend_color" value="5" @if (\App\GeneralSetting::first()->frontend_color == '5') checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="card-body text-center" style="background:#8e44ad;">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="frontend_color" value="6" @if (\App\GeneralSetting::first()->frontend_color == '6') checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card" >
                                    <div class="card-body text-center" style="background:#ED4C67;">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="frontend_color" value="7" @if (\App\GeneralSetting::first()->frontend_color == '7') checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="mb-3 text-right">
                        <button type="submit" name="button" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </form>
                <!--===================================================-->
                <!--End Horizontal Form-->

            </div>
        </div>
        <div class="col-lg-3"></div>
    </div>
@endsection
