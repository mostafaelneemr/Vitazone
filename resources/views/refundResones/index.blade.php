@extends('layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('refundResones') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('refundResones.create') }}" class="btn btn-circle btn-info">
                    <span>{{ translate('Add New refundResone') }}</span>
                </a>
            </div>
        </div>
    </div>
    <br>
    <div class="card">
        <form class="" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('refundResones') }}</h5>
                </div>
                <div class="col-lg-2">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="search" name="search" @isset($sort_search)
                            value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type Order code & hit Enter') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Arabic Resone') }}</th>
                        <th>{{ translate('English Resone') }}</th>
                        <th width="10%">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($refundResones as $key => $refundResone)
                        <tr>
                            <td>{{ $key + 1 + ($refundResones->currentPage() - 1) * $refundResones->perPage() }}</td>
                            <td>{{ $refundResone->resone_ar }}</td>
                            <td>{{ $refundResone->resone_en }}</td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{ route('refundResones.edit', encrypt($refundResone->id)) }}"
                                    title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>


                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    data-href="{{ route('refundResones.destroy', $refundResone->id) }}"
                                    title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $refundResones->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection
