@extends('layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Start Pages') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('startPages.create') }}" class="btn btn-circle btn-info">
                    <span>{{ translate('Add New Link') }}</span>
                </a>
            </div>
        </div>
    </div>
    <br>

    <div class="card">

        <div class="card-body">
            <table class="table aiz-table mb-0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th>{{ translate('Image') }}</th>
                        <th>{{ translate('Title') }}</th>
                        <th>{{ translate('Sub title') }}</th>
                        <th width="10%">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $key => $page)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <img src="{{ uploaded_asset($page->image) }}" alt="Image" class="w-50px">

                            </td>
                            <td>{{ $page->{'title_' . locale()} }}</td>
                            <td>{{ $page->{'sub_title_' . locale()} }}</td>

                            <td>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{ route('startPages.edit', encrypt($page->id)) }}"
                                    title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    data-href="{{ route('startPages.destroy', $page->id) }}"
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
@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection
