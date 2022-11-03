@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @include('frontend.inc.customer_side_nav')
                </div>
                <div class="col-lg-9">
                    <!-- Page title -->
                    <div class="page-title">
                        <div class="row align-items-center">
                            <div class="col-md-6 col-12">
                                <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                    {{ translate('Affiliate') }}
                                </h2>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="float-md-right">
                                    <ul class="breadcrumb">
                                        <li><a href="{{ route('home') }}">{{ translate('Home') }}</a></li>
                                        <li class="active"><a href="{{ route('dashboard') }}">{{ translate('Dashboard') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- dashboard content -->
                    <div class="">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="dashboard-widget text-center green-widget mt-4 c-pointer">
                                    <a href="javascript:;" class="d-block">
                                        <i class="fa fa-dollar"></i>                                  
                                            <span class="d-block title">{{ single_price(Auth::user()->affiliate_user->balance) }}</span>
                                        <span class="d-block sub-title">{{ translate('Affiliate Balance') }}</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="dashboard-widget text-center red-widget mt-4 c-pointer">
                                    <a href="{{ route('affiliate.payment_settings') }}" class="d-block">
                                        <i class="fa fa-dollar"></i>
                                        <span class="d-block sub-title">{{ translate('Configure Payout') }}</span>
                                        <span><br></span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="dashboard-widget text-center yellow-widget mt-4 c-pointer" onclick="show_affiliate_withdraw_modal()">
                                    <a class="d-block">
                                        <i class="fa fa-plus"></i>
                                       
                                        <span class="d-block title">{{  translate('Affiliate Withdraw Request') }}</span>
                                        <span><br></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <br>
                        @if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated && \App\AffiliateOption::where('type', 'user_registration_first_purchase')->first()->status)
                        <div class="row">
                            @php
                                if(Auth::user()->referral_code == null){
                                    Auth::user()->referral_code = substr(Auth::user()->id.Str::random(), 0, 10);
                                    Auth::user()->save();
                                }
                                $referral_code = Auth::user()->referral_code;
                                $referral_code_url = URL::to('/users/registration')."?referral_code=$referral_code";
                            @endphp
                            <div class="col">
                                <div class="card">
                                    <div class="form-box-content p-3">
                                        <div class="form-group">
                                            <textarea id="referral_code_url" class="form-control" readonly type="text" >{{$referral_code_url}}</textarea>
                                        </div>
                                        <button type=button id="ref-cpurl-btn" class="btn btn-primary float-right" data-attrcpy="{{translate('Copied')}}" onclick="copyToClipboard('url')" >{{translate('Copy Url')}}</button>
                                   
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <br>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{translate('Affiliate Earning History')}}</h5>
                        </div>
                        <div class="card-body">
                            <table class="table aiz-table mb-0">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ translate('Referral User')}}</th>
                                    <th>{{ translate('Amount')}}</th>
                                    <th>{{ translate('Order Id')}}</th>
                                    <th>{{ translate('Referral Type') }}</th>
                                    <th>{{ translate('Product') }}</th>
                                    <th>{{ translate('Date') }}</th>
                                </thead>
                                <tbody>
                                @foreach($affiliate_logs as $key => $affiliate_log)
                                    <tr>
                                        <td>{{ ($key+1) + ($affiliate_logs->currentPage() - 1)*$affiliate_logs->perPage() }}</td>
                                        <td>
                                            @if($affiliate_log->user_id !== null)
                                                {{ $affiliate_log->user->name }}
                                            @else
                                                {{ translate('Guest').' ('. $affiliate_log->guest_id.')' }}
                                            @endif
                                        </td>
                                        <td>{{ single_price($affiliate_log->amount) }}</td>
                                        <td>
                                            @if($affiliate_log->order_id != null)
                                                {{ $affiliate_log->order->code }}
                                            @else
                                                {{ $affiliate_log->order_detail->order->code }}
                                            @endif
                                        </td>
                                        <td> {{ ucwords(str_replace('_',' ', $affiliate_log->affiliate_type)) }}</td>
                                        <td>
                                            @if($affiliate_log->order_detail_id != null)
                                                {{ $affiliate_log->order_detail->product->name }}
                                            @endif
                                        </td>
                                        <td>{{ $affiliate_log->created_at->format('d, F Y') }} </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="aiz-pagination">
                                {{ $affiliate_logs->links() }}
                            </div>
                        </div>
                    </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="affiliate_withdraw_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Affiliate Withdraw Request') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <form class="" action="{{ route('affiliate.withdraw_request.store') }}" method="post">
                    @csrf
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ translate('Amount')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <input type="number" class="form-control mb-3" name="amount" min="1" max="{{ Auth::user()->affiliate_user->balance }}" placeholder="{{ translate('Amount')}}" required>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-sm btn-primary transition-3d-hover mr-1">{{translate('Confirm')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



@section('script')
    <script>
        function copyToClipboard(btn){
            var el_url = document.getElementById('referral_code_url');
            var c_u_b = document.getElementById('ref-cpurl-btn');
            if(btn == 'url'){
                if(el_url != null && c_u_b != null){
                    el_url.select();
                    document.execCommand('copy');
                    c_u_b .innerHTML  = c_u_b.dataset.attrcpy;
                }
            }
        }
        function show_affiliate_withdraw_modal(){
            $('#affiliate_withdraw_modal').modal('show');
        }
    </script>
@endsection
