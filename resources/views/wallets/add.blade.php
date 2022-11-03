@extends('layouts.app')

@section('content')
    <form class="form-horizontal" action="{{ route('wallets.store') }}" method="POST" enctype="multipart/form-data">
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
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Charge Wallet') }}</h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-3 col-form-label" for="amount">{{ translate('Charge amount') }}</label>
                    <div class="col-md-9">
                        <input type="text" placeholder="{{ translate('Charge amount') }}" id="amount" name="amount"
                            class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{ translate('User') }}</label>
                    <div class="col-md-9">
                        <select class="select2 form-control aiz-selectpicker" name="user_id" data-toggle="select2"
                            data-placeholder="Choose ..." data-live-search="true">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                </div>
            </div>
        </div>

    </form>

@endsection
