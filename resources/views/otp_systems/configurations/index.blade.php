@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Twillo Credential')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('update_credentials') }}" method="POST">
                        <input type="hidden" name="otp_method" value="twillo">
                        @csrf
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="TWILIO_SID">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('TWILIO SID')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="TWILIO_SID" value="{{  env('TWILIO_SID') }}" placeholder="TWILIO SID" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="TWILIO_AUTH_TOKEN">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('TWILIO AUTH TOKEN')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="TWILIO_AUTH_TOKEN" value="{{  env('TWILIO_AUTH_TOKEN') }}" placeholder="TWILIO AUTH TOKEN" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="TWILIO_VERIFY_SID">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('TWILIO VERIFY SID')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="TWILIO_VERIFY_SID" value="{{  env('TWILIO_VERIFY_SID') }}" placeholder="TWILIO VERIFY SID" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="VALID_TWILLO_NUMBER">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('VALID TWILLO NUMBER')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="VALID_TWILLO_NUMBER" value="{{  env('VALID_TWILLO_NUMBER') }}" placeholder="VALID TWILLO NUMBER" >
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Nexmo Credential')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('update_credentials') }}" method="POST">
                        <input type="hidden" name="otp_method" value="nexmo">
                        @csrf
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="NEXMO_KEY">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('NEXMO KEY')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="NEXMO_KEY" value="{{  env('NEXMO_KEY') }}" placeholder="NEXMO KEY" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="NEXMO_SECRET">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('NEXMO SECRET')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="NEXMO_SECRET" value="{{  env('NEXMO_SECRET') }}" placeholder="NEXMO SECRET" required>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('VictoryLink Credential')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('update_credentials') }}" method="POST">
                        <input type="hidden" name="otp_method" value="VictoryLink">
                        @csrf
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="VIKTORY_USER">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('VIKTORY USER')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="VIKTORY_USER" value="{{  env('VIKTORY_USER') }}" placeholder="VIKTORY USER" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="VIKTORY_SECRET">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('VIKTORY SECRET')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="VIKTORY_SECRET" value="{{  env('VIKTORY_SECRET') }}" placeholder="VIKTORY SECRET" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="VIKTORY_SENDER">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('VIKTORY SENDER')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="VIKTORY_SENDER" value="{{  env('VIKTORY_SENDER') }}" placeholder="VIKTORY SENDER" required>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Misrsms Credential')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('update_credentials') }}" method="POST">
                        <input type="hidden" name="otp_method" value="Misrsms">
                        @csrf
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="MISRSMS_USER">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('MISRSMS USER')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="MISRSMS_USER" value="{{  env('MISRSMS_USER') }}" placeholder="MISRSMS USER" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="MISRSMS_SECRET">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('MISRSMS SECRET')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="MISRSMS_SECRET" value="{{  env('MISRSMS_SECRET') }}" placeholder="MISRSMS SECRET" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="MISRSMS_SENDER">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('MISRSMS SENDER')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="MISRSMS_SENDER" value="{{  env('MISRSMS_SENDER') }}" placeholder="MISRSMS SENDER" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="MISRSMS_TOKEN">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('MISRSMS TOKEN')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="MISRSMS_TOKEN" value="{{  env('MISRSMS_TOKEN') }}" placeholder="MISRSMS TOKEN" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="MISRSMS_SIGNTURE">
                            <div class="col-lg-3">
                                <label class="col-from-label">{{translate('MISRSMS SIGNTURE')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="MISRSMS_SIGNTURE" value="{{  env('MISRSMS_SIGNTURE') }}" placeholder="MISRSMS SIGNTURE" required>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">

    </script>
@endsection
