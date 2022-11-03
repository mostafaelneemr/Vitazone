@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Staff Information') }}</h5>
                </div>

                <form class="form-horizontal" action="{{ route('staffs.update', $staff->id) }}" method="POST"
                    enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PATCH">
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
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{ translate('Name') }}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{ translate('Name') }}" id="name" name="name"
                                    value="{{ $staff->user->name }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="email">{{ translate('Email') }}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{ translate('Email') }}" id="email" name="email"
                                    value="{{ $staff->user->email }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="phone">{{ translate('Phone') }}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{ translate('Phone') }}" id="phone" name="phone"
                                    value="{{ $staff->user->phone }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="password">{{ translate('Password') }}</label>
                            <div class="col-sm-9">
                                <input type="password" placeholder="{{ translate('Password') }}" id="password"
                                    name="password" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{ translate('Role') }}</label>
                            <div class="col-sm-9">
                                <select name="role_id" required class="form-control aiz-selectpicker">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @php
                                            if ($staff->role_id == $role->id) {
                                                echo 'selected';
                                            }
                                        @endphp>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group mr-2" role="group" aria-label="First group">
                                    <button type="submit" name="button" value="save" class="btn btn-warning">{{ translate('Save') }}</button>
                                </div>
                                <div class="btn-group mr-2" role="group" aria-label="Third group">
                                    <button type="submit" name="button" value="update" class="btn btn-primary">{{ translate('Update') }}</button>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
