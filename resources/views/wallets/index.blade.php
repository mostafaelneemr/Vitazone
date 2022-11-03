@extends('layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Wallets') }}</h1>
            </div>
            @if (Auth::user()->user_type == 'admin' || in_array('18', json_decode(Auth::user()->staff->role->permissions)))
            <div class="col-md-6 text-md-right">
                <a href="{{ route('wallets.create') }}" class="btn btn-circle btn-info">
                    <span>{{ translate('Charge User Wallet') }}</span>
                </a>
            </div>
            @endif
        </div>
    </div>
    <br>
    <!-- Basic Data Tables -->
    <!--===================================================-->
    <div class="card">
        <div class="card-header row gutters-5">
            <h3 class="mb-md-0 h6 pull-left pad-no">{{ translate('Wallets') }}</h3>
            <div class="pull-right clearfix">
                <form class="" id="sort_categories" action="" method="GET">
                    <div class="box-inline pad-rgt pull-left">
                        <div class="" style="min-width: 200px;">
                            <input type="text" class="form-control" id="search" name="search" @isset($sort_search)
                                value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('User') }}</th>
                        <th>{{ translate('amount') }}</th>
                        <th>{{ translate('Payment method') }}</th>
                        <th>{{ translate('Payment details') }}</th>
                        @if (\App\BusinessSetting::where('type', 'fawry')->first()->value == 1)
                            <th>{{ translate('Fawry ref num') }}</th>
                        @endif
                        <th>{{ translate('approval') }}</th>
                        <th>{{ translate('created_at') }}</th>
                        <th width="10%">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($wallets as $key => $wallet)
                    @if ($wallet->user != null)
                          <tr>
                            <td>{{ $key + 1 + ($wallets->currentPage() - 1) * $wallets->perPage() }}</td>
                            <td>{{ $wallet->user->name }}</td>
                            <td>{{ $wallet->amount }}</td>
                            <td>{{ translate($wallet->payment_method) }}</td>
                            <td>{{ translate($wallet->payment_details) }}</td>
                            @if (\App\BusinessSetting::where('type', 'fawry')->first()->value == 1)
                                <td>{{ $wallet->fawry_ref_num }}</td>
                            @endif
                            <td>{{ $wallet->approval == 1 ? translate('Approved') : translate('Pending') }}</td>
                            <td>{{ $wallet->created_at }}</td>

                            <td>
                                @if ($wallet->approval == 0)
                                    <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                    href="{{ route('wallets.edit', encrypt($wallet->id)) }}"
                                    title="{{ translate('Approve') }}">
                                    <i class="las la-check-double"></i>
                                    </a>
                                    <a class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                    href="{{ route('wallets.reject', encrypt($wallet->id)) }}"
                                    title="{{ translate('Reject') }}">
                                    <i class="las la-times"></i>
                                    </a>
                                    
                                        @elseif($wallet->approval == 2)
                                        <i style="font-size: 1.5em;color: red" class="las la-times"></i>
                                @else
                                <i style="font-size: 1.5em;color: green" class="las la-check-double"></i>
                                @endif
                            </td>
                        </tr>
                    @endif
                      
                    @endforeach
                    
                </tbody>
            </table>
            <div class="clearfix">
                <div class="pull-right">
                    {{ $wallets->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
