@extends('layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('Attributes') }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Attributes') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Name') }}</th>
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attributes as $key => $attribute)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $attribute->name }}</td>
                                    <td class="text-right">
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ route('attributes.edit', encrypt($attribute->id)) }}"
                                            title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('attributes.destroy', $attribute->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Add New Attribute') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('attributes.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name_en">{{ translate('Name English') }}</label>
                            <input type="text" placeholder="{{ translate('Name English') }}" id="name_en" name="name_en"
                                class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name_ar">{{ translate('Name Arabic') }}</label>
                            <input type="text" placeholder="{{ translate('Name Arabic') }}" id="name_ar" name="name_ar"
                                class="form-control" required>
                        </div>
                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
