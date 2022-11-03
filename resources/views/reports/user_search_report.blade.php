@extends('layouts.app')

@section('content')


<div class="col-md-8 mx-auto">
    <div class="card">
		<div class="card-header">
			<h1 class="h6">{{translate('User Search Report')}}</h1>
            @if (Auth::user()->user_type == 'admin')
                <div class="col-md-6 text-md-right">
                    <a href="{{ route('user_search_report.clear') }}" class="btn btn-circle btn-info">
                        <span>{{ translate('Clear Searches') }}</span>
                    </a>
                </div>
            @endif
		</div>
        <div class="card-body">
            <table class="table table-bordered aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Search By') }}</th>
                        <th>{{ translate('Number searches') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($searches as $key => $searche)
                        <tr>
                            <td>{{ ($key+1) + ($searches->currentPage() - 1)*$searches->perPage() }}</td>
                            <td>{{ $searche->query }}</td>
                            <td>{{ $searche->count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination mt-4">
                {{ $searches->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
