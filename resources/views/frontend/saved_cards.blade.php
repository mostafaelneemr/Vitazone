@extends('frontend.layouts.app')

@section('content')
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
                                        {{ translate('Saved Cards') }}
                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{ translate('Home') }}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{ translate('Dashboard') }}</a></li>
                                            <li class="active"><a
                                                    href="{{ route('saved_cards') }}">{{ translate('Saved Cards') }}</a>
                                            </li>
                                        </ul>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card no-border mt-5">
                            <div class="card-header py-3">
                                <h4 class="mb-0 h6">{{ translate('Saved Cards') }}</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-responsive-md mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ translate('Card Num') }}</th>
                                            <th>{{ translate('Card Type') }}</th>
                                            <th>{{ translate('Options') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($saved_cards) > 0)
                                            @foreach ($saved_cards as $key => $saved_card)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $saved_card->masked_pan }}</td>
                                                    <td>{{ $saved_card->card_subtype }}</td>
                                                    <td><a href="{{ route('saved_cards.delete',$saved_card->id) }}"><i class="la la-trash"  title="{{ translate('Delete') }}"></i></a></td>

                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="text-center pt-5 h4" colspan="100%">
                                                    <i class="la la-meh-o d-block heading-1 alpha-5"></i>
                                                    <span class="d-block">{{ translate('No cards found.') }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="pagination-wrapper py-4">
                            <ul class="pagination justify-content-end">
                                {{ $saved_cards->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript"></script>
@endsection
