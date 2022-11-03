@extends('frontend.layouts.app')

@section('content')
    @php
    $club_point_convert_rate = \App\BusinessSetting::where('type', 'club_point_convert_rate')->first()->value;
    @endphp
    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @if (Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
                    @endif
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12 d-flex align-items-center">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{ translate('My Points') }}
                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{ translate('Home') }}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{ translate('Dashboard') }}</a>
                                            </li>
                                            <li class="active"><a
                                                    href="{{ route('earnng_point_for_user') }}">{{ translate('My Points') }}</a>
                                            </li>
                                        </ul>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">

                                <div class="dashboard-widget text-center green-widget text-white mt-4 c-pointer">
                                    <div class="h3 fw-700 text-center">{{ $club_point_convert_rate }}
                                        {{ translate(' Points') }} = {{ single_price(1) }}
                                        {{ translate('Wallet Money') }}</div>
                                    <div class="opacity-50 text-center">{{ translate('Exchange Rate') }}</div>

                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>

                        </div>

                        <div class="card no-border mt-5">
                            <div class="card-header py-3">
                                <h4 class="mb-0 h6">{{ translate('Point Earning history') }}</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-responsive-md mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ translate('Order Code') }}</th>
                                            <th data-breakpoints="lg">{{ translate('Points') }}</th>
                                            <th data-breakpoints="lg">{{ translate('Converted') }}</th>
                                            <th data-breakpoints="lg">{{ translate('Date') }}</th>
                                            <th class="text-right">{{ translate('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($club_points as $key => $club_point)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    @if ($club_point->order_id != null)
                                                        {{ $club_point->order->code }}
                                                    @endif
                                                </td>
                                                <td>{{ $club_point->points }} {{ translate(' pts') }}</td>
                                                <td>
                                                    @if ($club_point->convert_status == 1)
                                                        <span
                                                            class="badge badge-inline badge-success">{{ translate('Yes') }}</strong></span>
                                                    @else
                                                        <span
                                                            class="badge badge-inline badge-info">{{ translate('No') }}</strong></span>
                                                    @endif
                                                </td>
                                                <td>{{ date('d-m-Y', strtotime($club_point->created_at)) }}</td>

                                                <td class="text-right">
                                                    @if ($club_point->convert_status == 0)
                                                        <button onclick="convert_point({{ $club_point->id }})"
                                                            class="btn btn-sm btn-styled btn-primary">{{ translate('Convert Now') }}</button>
                                                    @else
                                                        <span
                                                            class="badge badge-inline badge-success">{{ translate('Done') }}</span>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="pagination-wrapper py-4">
                            <ul class="pagination justify-content-end">
                                {{ $club_points->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('script')
    <script type="text/javascript">
        function convert_point(el) {
            $.post('{{ route('convert_point_into_wallet') }}', {
                _token: '{{ csrf_token() }}',
                el: el
            }, function(data) {
                if (data == 1) {
                    location.reload();
                    AIZ.plugins.notify('success',
                        '{{ translate('Convert has been done successfully Check your Wallets') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

    </script>
@endsection
