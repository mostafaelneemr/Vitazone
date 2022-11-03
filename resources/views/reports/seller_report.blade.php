@extends('layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class=" align-items-center">
            <h1 class="h3">{{ translate('Seller report') }}</h1>
        </div>
    </div>
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('seller_report.index') }}" method="GET">
                    <div class="form-group row offset-lg-2">
                        <label class="col-md-3 col-form-label">{{ translate('Sort by verificarion status') }} :</label>
                        <div class="col-md-5">
                            <select class="from-control aiz-selectpicker" name="verification_status" required>
                                <option value="1">{{ translate('Approved') }}</option>
                                <option value="0">{{ translate('Non Approved') }}</option>
                            </select>
                        </div>
                        <div class="cil-md-2">
                            <button class="btn btn-light" type="submit">{{ translate('Filter') }}</button>
                        </div>
                    </div>
                </form>

                <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Seller Name') }}</th>
                            <th>{{ translate('Email') }}</th>
                            <th>{{ translate('Shop Name') }}</th>
                            <th>{{ translate('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sellers as $key => $seller)
                            @if ($seller->user != null)
                                <tr>
                                    <td>{{ $seller->user->name }}</td>
                                    <td>{{ $seller->user->email }}</td>
                                    <td>{{ $seller->user->shop ? $seller->user->shop->{'name_' . locale()} : '' }}</td>
                                    <td>
                                        @if ($seller->verification_status == 1)
                                            <div class="label label-table label-success">
                                                {{ translate('Verified') }}
                                            </div>
                                        @elseif ($seller->verification_info != null)
                                            <a href="{{ route('sellers.show_verification_request', $seller->id) }}">
                                                <div class="label label-table label-info">
                                                    {{ translate('Requested') }}
                                                </div>
                                            </a>
                                        @else
                                            <div class="label label-table label-danger">
                                                {{ translate('Not Verified') }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination mt-4">
                    {{ $sellers->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
